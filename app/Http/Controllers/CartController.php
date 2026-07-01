<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display the shopping cart.
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return view('cart', compact('cart', 'total'));
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        $qtyToAdd = $request->quantity;

        // Check stock
        if ($product->stock < $qtyToAdd) {
            return redirect()->back()->with('error', "Stok tidak mencukupi! Stok saat ini: {$product->stock}");
        }

        $cart = session()->get('cart', []);

        // If product already in cart, update quantity
        if (isset($cart[$product->id])) {
            $newQty = $cart[$product->id]['quantity'] + $qtyToAdd;
            
            if ($product->stock < $newQty) {
                return redirect()->back()->with('error', "Stok tidak mencukupi untuk jumlah total di keranjang Anda! Stok: {$product->stock}");
            }
            
            $cart[$product->id]['quantity'] = $newQty;
        } else {
            // Add new product to cart
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $qtyToAdd,
                'image' => $product->image,
                'slug' => $product->slug,
                'max_stock' => $product->stock
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', "{$product->name} berhasil ditambahkan ke keranjang!");
    }

    /**
     * Update cart item quantity.
     */
    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $qty = $request->quantity;

            if ($product->stock < $qty) {
                return response()->json([
                    'success' => false,
                    'message' => "Stok tidak mencukupi! Stok saat ini: {$product->stock}"
                ], 400);
            }

            $cart[$product->id]['quantity'] = $qty;
            session()->put('cart', $cart);

            // Recalculate total
            $subtotal = $cart[$product->id]['price'] * $qty;
            $total = 0;
            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            return response()->json([
                'success' => true,
                'subtotal' => number_format($subtotal, 0, ',', '.'),
                'total' => number_format($total, 0, ',', '.'),
                'message' => 'Keranjang berhasil diperbarui!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Produk tidak ditemukan di keranjang!'
        ], 404);
    }

    /**
     * Remove product from cart.
     */
    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required'
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$request->product_id])) {
            unset($cart[$request->product_id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang!');
    }
}
