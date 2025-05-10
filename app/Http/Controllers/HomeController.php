<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Stripe;

class HomeController extends Controller
{
    public function index()
    {
        $userCount = User::where('usertype', 'user')->count();
        $productCount = Product::count();
        $orderCount = Order::count();
        $deliveredCount = Order::where('status', 'Delivered')->count();

        return view('admin.index', compact('userCount', 'productCount', 'orderCount', 'deliveredCount'));
    }

    public function home()
    {
        $products = Product::all();
        $count = $this->getCartCount();

        return view('home.index', compact('products', 'count'));
    }

    public function loginHome()
    {
        $products = Product::all();
        $count = $this->getCartCount();

        return view('home.index', compact('products', 'count'));
    }

    public function productDetails($id)
    {
        $product = Product::findOrFail($id);
        $count = $this->getCartCount();

        return view('home.product_details', compact('product', 'count'));
    }

    public function addCart($id)
    {
        $user = Auth::user();

        Cart::create([
            'user_id' => $user->id,
            'product_id' => $id,
        ]);

        toastr()->timeOut(10000)->closeButton()->addSuccess('Product added to the cart successfully.');

        return redirect()->back();
    }

    public function myCart()
    {
        $user = Auth::user();
        $count = Cart::where('user_id', $user->id)->count();
        $cartItems = Cart::where('user_id', $user->id)->get();

        return view('home.mycart', compact('count', 'cartItems'));
    }

    public function deleteCart($id)
    {
        Cart::destroy($id);

        toastr()->timeOut(10000)->closeButton()->addSuccess('Product removed from the cart successfully.');

        return redirect()->back();
    }

    public function confirmOrder(Request $request)
    {
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->get();

        foreach ($cartItems as $item) {
            Order::create([
                'product_id' => $item->product_id,
                'name' => $request->name,
                'rec_address' => $request->address,
                'phone' => $request->phone,
                'user_id' => $user->id,
            ]);
        }

        Cart::where('user_id', $user->id)->delete();

        toastr()->timeOut(10000)->closeButton()->addSuccess('Order placed successfully.');

        return redirect()->back();
    }

    public function myOrders()
    {
        $user = Auth::user();
        $count = Cart::where('user_id', $user->id)->count();
        $orders = Order::where('user_id', $user->id)->get();

        return view('home.order', compact('count', 'orders'));
    }

    public function stripe($value)
    {
        return view('home.stripe', compact('value'));
    }

    public function stripePost(Request $request, $value)
    {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        Stripe\Charge::create([
            'amount' => $value * 100,
            'currency' => 'usd',
            'source' => $request->stripeToken,
            'description' => 'Order payment',
        ]);

        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->get();

        foreach ($cartItems as $item) {
            Order::create([
                'product_id' => $item->product_id,
                'name' => $user->name,
                'rec_address' => $user->address,
                'phone' => $user->phone,
                'user_id' => $user->id,
                'payment_status' => 'paid',
            ]);
        }

        Cart::where('user_id', $user->id)->delete();

        toastr()->timeOut(10000)->closeButton()->addSuccess('Order placed successfully.');

        return redirect()->route('mycart');
    }

    public function shop()
    {
        $products = Product::all();
        $count = $this->getCartCount();

        return view('home.shop', compact('products', 'count'));
    }

    public function why()
    {
        $count = $this->getCartCount();

        return view('home.why', compact('count'));
    }

    public function testimonial()
    {
        $count = $this->getCartCount();

        return view('home.testimonial', compact('count'));
    }

    public function contact()
    {
        $count = $this->getCartCount();

        return view('home.contact', compact('count'));
    }

    private function getCartCount()
    {
        return Auth::check() ? Cart::where('user_id', Auth::id())->count() : 0;
    }
}
