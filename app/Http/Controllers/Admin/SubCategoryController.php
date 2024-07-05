<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function Index() {
        $allsubcategories = Subcategory::latest()->get();
        return view('admin.allsubcategory', compact('allsubcategories'));
    }

    public function AddSubCategory() {
        $categores = Category::latest()->get();
        return view('admin.addsubcategory', compact('categores'));
    }

    public function Storesubcategory(Request $request) {
        $request->validate([
            'subcategory_name' => 'required|unique:sub_categories',
            'category_id' =>'required'
        ]);

        $category_id = $request->category_id;

        $category_name = Category::where('id', $category_id)->value('category_name');

        Subcategory::insert([
            'subcategory_name' => $request->subcategory_name,
            'slug' => strtolower(str_replace(' ', '-', $request->subcategory_name)),
            'category_id' => $category_id,
            'category_name' => $category_name
        ]);

        category::where('id', $category_id)->increment('subcategory_count', 1);

        return redirect()->route('allsubcategory')->with('massege', 'SubCategory Added Successfully!');
    }

    public function Editsubcat($id)  {
        $subcatinfo = subcategory::findOrfail($id);

        return view('admin.editsubcat', compact('subcatinfo'));
    }

    public function Updatesubcat(Request $request) {
        $request->validate([
            'subcategory_name' => 'required|unique:sub_categories'
        ]);

        $subcatid = $request->subcatid;

        subcategory::findOrfail($subcatid)->update([
            'subcategory_name' => $request->subcategory_name,
            'slug' =>strtolower(str_replace(' ', '-', $request->subcategory_name))
        ]);

        return redirect()->route('allsubcategory')->with('massege', 'SubCategory Updated Successfully!');
    }
    
    public function Deletesubcat($id) {
        $cat_id = subcategory::where('id', $id)->value('category_id');
        subcategory::findOrFail($id)->delete();
        category::where('id', $cat_id)->decrement('subcategory_count', 1);

        return redirect()->route('allsubcategory')->with('massege', 'Sub Category Deleted Successfully!');
    }
}
