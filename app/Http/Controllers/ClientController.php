<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\product;
use Illuminate\Http\Request;

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
        return view('user_template.addtocart');
    }
    public function Checkout() {
        return view('user_template.checkout');
    }
    public function UserProfile() {
        return view('user_template.userprofile');
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
