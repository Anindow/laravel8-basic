<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;



class CategoryController extends Controller
{
    public function AllCat(){
      //  $categories =Category::latest()->get();
    $categories =Category::latest()->paginate(5);
    $trachCat = Category::onlyTrashed()->latest()->paginate(2);
    
        return view('admin.category.index' , compact('categories','trachCat'));
    }


    public function AddCat(Request $request){
        $validatedData = $request->validate([
            'category_name' => 'required|unique:categories|max:255',
        ],
        [
            'category_name.required' => 'PLease Input Category Name',
        ]);

    Category::insert([
        'category_name' => $request->category_name,
        'user_id' => Auth::user()->id,
        'created_at'=> Carbon::now()
    ]); 
    /*
    $category = new Category;
    $category->category_name = $request->category_name;
    $category->user_id = Auth::user()->id;
    $category->save(); */
    
    return Redirect()->back()->with('success','Category Inserted Successfull');


    }

    public function Edit($id){
        $categories = Category::find($id);
        return view('admin.category.edit',compact('categories'));
    }
    public function Update(Request $request,$id){
        $updat = Category::find($id)->update([
            'category_name' => $request->category_name,
            'user_id' => Auth::user()->id
        ]);
        return Redirect()->route('all.category')->with('success','Category Updated Successfull');
    }
    public function SoftDelete($id){
        $delete = Category::find($id)->delete();
        return Redirect()->back()->with('success','Soft Delete Successfull');
    }
}
