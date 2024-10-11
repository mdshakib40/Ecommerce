<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\product;
use App\Models\Shipping_Info;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function CategoryPage($id) {
        $category = Category::findOrfail($id);
        $products = product::where('product_category_id', $id)->latest()->get();
        return view('user_template.category', compact('category', 'products'));
    }


    public function Singleproduct($id) {
        $product = product::findOrFail($id);
        $subcat_id = product::where('id', $id)->value('product_subcategory_id');
        $related_products = product::where('product_subcategory_id', $subcat_id)->latest()->get();
        return view('user_template.product', compact('product', 'related_products'));
    }

    public function AddTocart() {
        $userid = Auth::id();
        $cart_itmes = Cart::where('user_id', $userid)->get();
        return view('user_template.addtocart', compact('cart_itmes'));
    }

    public function AddProductToCart(Request $request) {
        $product_price = $request->price;                              
        $quantity = $request->quantity;
        $price = $product_price * $quantity;
        Cart::insert([
            'product_id' =>$request->product_id,
            'user_id' =>Auth::id(),
            'quantity' => $request->quantity,
            'price' => $price
        ]);

        return redirect()->route('addtocart')->with('massege', 'Your Item Added to Successfully!');
    }

    public function RemoveCartItem($id) {
        Cart::findOrFail($id)->delete();
        return redirect()->route('addtocart')->with('massege', 'Your Item Remove From Cart Successfully!');
    }

    public function AddshippingAddress(Request $request){
        Shipping_Info::insert([
            'user_id' => Auth::id(),
            'phone_number' => $request->phone_number,
            'city_name' => $request->city_name,
            'postal_code' => $request->postal_code,
        ]);

        return redirect()->route('checkout');
    }


    public function GetShippingAddress() {
        return view('user_template.shippingaddress');
    }

    public function Checkout() {
        $userid = Auth::id();
        $cart_itmes = Cart::where('user_id', $userid)->get();
        $shipping_address = Shipping_Info::where('user_id', $userid)->first();
        return view('user_template.checkout', compact('cart_itmes', 'shipping_address'));
    }

    public function PlaceOrder() {
        $userid = Auth::id();
        $cart_items = Cart::where('user_id', $userid)->get();
        $shipping_address = Shipping_Info::where('user_id', $userid)->first();

        foreach($cart_items as $item) {
            Order::insert([
                'userid' => $userid,
                'shipping_phoneNumber' => $shipping_address->phone_number,
                'shipping_city' => $shipping_address->city_name,
                'shipping_postalcode' => $shipping_address->postal_code,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'total_price' => $item->price,
            ]);

            $id = $item->id;
            Cart::findOrFail($id)->delete();
        }

        Shipping_Info::where('user_id', $userid)->first()->delete();

       return redirect('user-profile/pending-orders')->with('massege', 'Your Order Has Been Placed Successfully!');

    }
    
    public function UserProfile() {
        return view('user_template.userprofile');
    }

    public function PendingOrders() {
        $pending_orders = Order::where('status', 'pending')->latest()->get();
        return view('user_template.pendingorders', compact('pending_orders'));
    }
    public function History() {
        return view('user_template.history');
    }

    public function NewRelease() {
        return view('user_template.newrelease');
    }
    public function TodaysDeal() {
        return view('user_template.todaysdeal');
    }
    public function CustomerService() {
        return view('user_template.customerservice');
    }
}
