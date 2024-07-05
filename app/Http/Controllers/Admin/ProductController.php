<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\product;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function Index() {
        $products = Product::latest()->get();
        return view('admin.allproduct', compact('products'));
    }

    public function AddProduct() {
        $categories = Category::latest()->get();
        $subcategories = Subcategory::latest()->get();
        return view('admin.addproduct', compact('categories', 'subcategories'));
    }

    public function StoreProduct(Request $request) {
        $request->validate([
            'product_name'           => 'required|unique:products',
            'price'                  => 'required',
            'quantity'               => 'required',
            'product_short_des'      => 'required',
            'product_long_des'       => 'required',
            'product_category_id'    => 'required',
            'product_subcategory_id' => 'required',
            'product_img'            => 'required|mimes:jpg,bmp,png,jpeg,gif,svg|max:2048',
        ]);

        $image = $request->file('product_img');
        $img_name = hexdec(uniqid()).'.'. $image->getClientOriginalExtension();
        $request->product_img->move(public_path('upload'),$img_name);
        $img_url = 'upload/' . $img_name;

        $category_id = $request->product_category_id;
        $subcategory_id = $request->product_subcategory_id;

        $category_name = Category::where('id', $category_id)->value('category_name');
        $subcategory_name = Subcategory::where('id', $subcategory_id)->value('subcategory_name');

        Product::insert([
            'product_name'             => $request->product_name,
            'product_short_des'        => $request->product_short_des,
            'product_long_des'         => $request->product_long_des,
            'price'                    => $request->price,
            'product_category_name'    => $category_name,
            'product_subcategory_name' => $subcategory_name,
            'product_category_id'      => $request->product_category_id,
            'product_subcategory_id'   => $request->product_subcategory_id,
            'product_img'              => $img_url,
            'quantity'                 => $request->quantity,
            'slug'                     => strtolower(str_replace(' ','-', $request->product_name)),
        ]);

        Category::where('id', $category_id)->increment('product_count', 1);
        Subcategory::where('id', $subcategory_id)->increment('product_count', 1);

        return redirect()->route('allproduct')->with('massege', 'Product Added Successfully !');
    }

    public function EditProductimg($id) {
        $productinfo = product::findOrFail($id);
        return view('admin.editproductimg', compact('productinfo'));
    }

    public function UpdateProductImg(Request $request) {
        $request->validate([
            'product_img' => 'required|mimes:jpg,bmp,png,jpeg,gif,svg|max:2048',
        ]);

        $id = $request->id;

        $image = $request->file('product_img');
        $img_name = hexdec(uniqid()).'.'. $image->getClientOriginalExtension();
        $request->product_img->move(public_path('upload'),$img_name);
        $img_url = 'upload/' . $img_name;

        product::findOrFail($id)->update([
            'product_img' => $img_url,
        ]);

        return redirect()->route('allproduct')->with('massege', 'Product Image Successfully !');
    }

    public function EditProduct( $id) {
        $productinfo = product::findOrFail($id);

        return view('admin.editproduct', compact('productinfo'));
    }

    public function UpdateProduct(Request $request) {
        $productid = $request->id;

        $request->validate([
            'product_name'           => 'required|unique:products',
            'price'                  => 'required',
            'quantity'               => 'required',
            'product_short_des'      => 'required',
            'product_long_des'       => 'required',
        ]);


        product::findOrFail($productid)->update([
            'product_name'             => $request->product_name,
            'product_short_des'        => $request->product_short_des,
            'product_long_des'         => $request->product_long_des,
            'price'                    => $request->price,
            'quantity'                 => $request->quantity,
            'slug'                     => strtolower(str_replace(' ','-', $request->product_name)),
        ]);

        return redirect()->route('allproduct')->with('massege', 'ProductInfo Updated Successfully !');
    }

    public function DeleteProduct($id) {
        $cat_id = product::where('id', $id)->value('product_category_id');
        $subcat_id = product::where('id', $id)->value('product_subcategory_id');
        category::where('id', $cat_id)->decrement('product_count', 1);
        subcategory::where('id', $subcat_id)->decrement('product_count', 1);
        product::findOrFail($id)->delete();

        return redirect()->route('allproduct')->with('massege', 'Product Deleted Successfully !');
    }
    
}


