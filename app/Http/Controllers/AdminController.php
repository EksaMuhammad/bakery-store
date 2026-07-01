<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Show admin login form.
     */
    public function showLogin()
    {
        if (session()->has('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    /**
     * Handle admin login attempt.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        // Simple auth for seeded admin
        if ($user && Hash::check($request->password, $user->password)) {
            session()->put('admin_logged_in', true);
            session()->put('admin_name', $user->name);
            return redirect()->route('admin.dashboard')->with('success', 'Selamat datang kembali, Admin!');
        }

        return redirect()->back()->withInput()->with('error', 'Email atau password salah.');
    }

    /**
     * Handle admin logout.
     */
    public function logout()
    {
        session()->forget('admin_logged_in');
        session()->forget('admin_name');
        return redirect()->route('admin.login')->with('success', 'Berhasil logout.');
    }

    /**
     * Check if admin is logged in before running actions.
     */
    private function checkAuth()
    {
        if (!session()->has('admin_logged_in')) {
            abort(403, 'Unauthorized. Please login first.');
        }
    }

    /**
     * Display admin dashboard.
     */
    public function dashboard()
    {
        if (!session()->has('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $totalRevenue = Order::where('status', 'completed')->sum('total_price');
        $pendingRevenue = Order::where('status', 'pending')->orWhere('status', 'processing')->sum('total_price');
        $totalOrders = Order::count();
        $completedOrders = Order::where('status', 'completed')->count();
        $totalProducts = Product::count();
        
        $recentOrders = Order::orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalRevenue', 
            'pendingRevenue',
            'totalOrders', 
            'completedOrders', 
            'totalProducts', 
            'recentOrders'
        ));
    }

    /**
     * List all products.
     */
    public function products()
    {
        if (!session()->has('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $products = Product::orderBy('created_at', 'desc')->get();
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show form to create product.
     */
    public function createProduct()
    {
        if (!session()->has('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $categories = [
            'breads' => 'Roti (Breads)',
            'cakes' => 'Kue (Cakes)',
            'pastries' => 'Pastri (Pastries)',
            'cookies' => 'Kue Kering (Cookies)'
        ];

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store new product in database.
     */
    public function storeProduct(Request $request)
    {
        if (!session()->has('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:breads,cakes,pastries,cookies',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'ingredients' => 'nullable|string',
            'allergens' => 'nullable|string',
        ]);

        $imageName = null;
        if ($request->hasFile('image_file')) {
            $imageFile = $request->file('image_file');
            $imageName = time() . '_' . Str::slug($request->name) . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move(public_path('images/products'), $imageName);
        }

        Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'category' => $request->category,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imageName ?? 'croissant.png', // default placeholder from our seeded assets
            'ingredients' => $request->ingredients,
            'allergens' => $request->allergens,
        ]);

        return redirect()->route('admin.products')->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Show form to edit product.
     */
    public function editProduct($id)
    {
        if (!session()->has('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $product = Product::findOrFail($id);
        
        $categories = [
            'breads' => 'Roti (Breads)',
            'cakes' => 'Kue (Cakes)',
            'pastries' => 'Pastri (Pastries)',
            'cookies' => 'Kue Kering (Cookies)'
        ];

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update product in database.
     */
    public function updateProduct(Request $request, $id)
    {
        if (!session()->has('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:breads,cakes,pastries,cookies',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'ingredients' => 'nullable|string',
            'allergens' => 'nullable|string',
        ]);

        $imageName = $product->image;
        if ($request->hasFile('image_file')) {
            // Delete old image if it exists and is not one of the default assets
            $defaultAssets = ['croissant.png', 'strawberry-cake.png', 'sourdough.png', 'cookies.png'];
            if ($product->image && !in_array($product->image, $defaultAssets)) {
                $oldPath = public_path('images/products/' . $product->image);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $imageFile = $request->file('image_file');
            $imageName = time() . '_' . Str::slug($request->name) . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move(public_path('images/products'), $imageName);
        }

        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'category' => $request->category,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imageName,
            'ingredients' => $request->ingredients,
            'allergens' => $request->allergens,
        ]);

        return redirect()->route('admin.products')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Delete product.
     */
    public function deleteProduct($id)
    {
        if (!session()->has('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $product = Product::findOrFail($id);

        // Delete image file if not a default seed asset
        $defaultAssets = ['croissant.png', 'strawberry-cake.png', 'sourdough.png', 'cookies.png'];
        if ($product->image && !in_array($product->image, $defaultAssets)) {
            $oldPath = public_path('images/products/' . $product->image);
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        $product->delete();

        return redirect()->route('admin.products')->with('success', 'Produk berhasil dihapus!');
    }

    /**
     * List all orders.
     */
    public function orders()
    {
        if (!session()->has('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $orders = Order::orderBy('created_at', 'desc')->get();
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * View detailed order.
     */
    public function orderDetail($id)
    {
        if (!session()->has('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $order = Order::with('items.product')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status.
     */
    public function updateOrderStatus(Request $request, $id)
    {
        if (!session()->has('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $order = Order::findOrFail($id);
        
        // If order gets cancelled, restock products
        if ($request->status == 'cancelled' && $order->status != 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }
        }
        
        // If order was cancelled and gets restored, decrement stock again
        if ($order->status == 'cancelled' && $request->status != 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->decrement('stock', $item->quantity);
                }
            }
        }

        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
    }
}
