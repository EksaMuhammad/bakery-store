<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page.
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('catalog')->with('error', 'Keranjang Anda kosong! Silakan pilih produk terlebih dahulu.');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('checkout', compact('cart', 'total'));
    }

    /**
     * Process the checkout details and save to database.
     */
    public function store(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('catalog')->with('error', 'Keranjang Anda kosong!');
        }

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'delivery_type' => 'required|in:pickup,delivery',
            'address' => 'required_if:delivery_type,delivery|nullable|string|max:500',
            'payment_method' => 'required|in:transfer,ewallet,cod',
        ], [
            'customer_name.required' => 'Nama lengkap wajib diisi.',
            'customer_email.required' => 'Email wajib diisi.',
            'customer_email.email' => 'Format email tidak valid.',
            'customer_phone.required' => 'Nomor WhatsApp wajib diisi.',
            'delivery_type.required' => 'Pilih jenis pengiriman.',
            'address.required_if' => 'Alamat pengiriman wajib diisi jika Anda memilih Delivery.',
            'payment_method.required' => 'Pilih metode pembayaran.',
        ]);

        // Wrap in transaction to ensure consistency
        DB::beginTransaction();

        try {
            // Verify stock first
            foreach ($cart as $cartItem) {
                $product = Product::find($cartItem['id']);
                if (!$product || $product->stock < $cartItem['quantity']) {
                    return redirect()->route('cart.index')->with('error', "Stok untuk produk '{$cartItem['name']}' tidak mencukupi atau produk tidak tersedia.");
                }
            }

            // Calculate total price
            $totalPrice = 0;
            foreach ($cart as $cartItem) {
                $totalPrice += $cartItem['price'] * $cartItem['quantity'];
            }

            // Generate order code, e.g., SCB-20260630-X9A2
            $orderCode = 'SCB-' . date('Ymd') . '-' . strtoupper(Str::random(4));

            // Create Order
            $order = Order::create([
                'order_code' => $orderCode,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'delivery_type' => $request->delivery_type,
                'address' => $request->delivery_type == 'delivery' ? $request->address : null,
                'payment_method' => $request->payment_method,
                'total_price' => $totalPrice,
                'status' => 'pending' // default status
            ]);

            // Create OrderItems and decrease stock
            foreach ($cart as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem['id'],
                    'quantity' => $cartItem['quantity'],
                    'price' => $cartItem['price']
                ]);

                // Update stock
                $product = Product::find($cartItem['id']);
                $product->decrement('stock', $cartItem['quantity']);
            }

            DB::commit();

            // Clear Cart
            session()->forget('cart');

            return redirect()->route('invoice', $order->order_code)->with('success', 'Pemesanan Anda berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem saat memproses pesanan Anda. Silakan coba lagi. ' . $e->getMessage());
        }
    }

    /**
     * Display the order invoice page for tracking.
     */
    public function invoice($order_code)
    {
        $order = Order::with('items.product')->where('order_code', $order_code)->firstOrFail();
        return view('invoice', compact('order'));
    }

    /**
     * Track order from a form.
     */
    public function track(Request $request)
    {
        $request->validate([
            'order_code' => 'required|string'
        ]);

        $order = Order::where('order_code', trim($request->order_code))->first();

        if (!$order) {
            return redirect()->back()->with('error', 'Kode pesanan tidak ditemukan. Silakan periksa kembali kode Anda.');
        }

        return redirect()->route('invoice', $order->order_code);
    }
}
