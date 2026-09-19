<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\Services;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminTechController extends Controller
{
    //adminpanel


    //display Servicess table

    public function index()
    {
        $pproducts = Services::with('category','availabilities')->orderBy('created_at', 'desc')->get();
        // return view('admin.pages.products.index', ['prroducts'=> $pproducts]);
        return view('laravel-examples/user-management', ['prroducts'=> $pproducts]);
    }

    public function create()
    {
        $categories = Category::all();
        $availabilities = Availability::all();
        return view('laravel-examples/user-create',['categories' => $categories, 'availabilities' => $availabilities]);
    }


    public function store(Request $request)
    {
        //validate
        $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'required',
            'availabilities' => 'required',
            'price' => 'required',
            // 'rating' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:8192',
        ], [
            // Without this, a file rejected by PHP's upload_max_filesize shows
            // only "The image failed to upload." with no hint as to why.
            'image.uploaded' => 'The image could not be uploaded - it is larger than the server limit (8 MB).',
            'image.max' => 'The image must be 8 MB or smaller.',
        ]);
        //store Image
        $image_name = 'products/'. time() . rand(0, 999) .'.'. $request->image->getClientOriginalExtension();
        $request->image->storeAs('public', $image_name);


        //store
        $product = Services::create([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'price' => $request->price,   //* 220, for rupee to Dollars
            // 'rating' => $request->rating,   //* 220, for rupee to Dollars
            'description' => $request->description,
            'image' => $image_name
        ]);

        $product->availabilities()->attach($request->availabilities);



        //return response

        // back() left the admin on an empty create form after a successful save.
        return redirect()->route('adminpanel.technicians')
            ->with('success', 'Service created successfully.');



    }


    public function edit($id)
    {
        $product = Services::findOrFail($id);
        $categories = Category::all();
        $availabilities = Availability::all();
        return view('laravel-examples/user-edit',['categories' => $categories, 'availabilities' => $availabilities, 'asdf' => $product]);
    
    }


    public function update(Request $request, $id)
    {
//validate
       $request->validate([
        'title' => 'required|max:255',
        'category_id' => 'required',
        'availabilities' => 'required',
        'price' => 'required',
        // 'rating' => 'required',
        'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:8192'
    ], [
        'image.uploaded' => 'The image could not be uploaded - it is larger than the server limit (8 MB).',
        'image.max' => 'The image must be 8 MB or smaller.',
    ]);
    $product = Services::findOrFail($id);
//store Image
    $image_name = $product -> image;
    if($request->image){
        $image_name = 'products/'. time() . rand(0, 999) .'.'. $request->image->getClientOriginalExtension();
        $request->image->storeAs('public', $image_name);    
    }
//store
    $product ->update([
        'title' => $request->title,
        'category_id' => $request->category_id,
        // Was $request->price * 100, while store() saves the price as entered.
        // Every edit multiplied the price by 100: 2500 -> 250000 -> 25000000.
        'price' => $request->price,
        // 'rating' => $request->rating * 100,   //* 220,  for rupee to Dollars
        'description' => $request->description,
        'image' => $image_name
    ]);
    $product->availabilities()->sync($request->availabilities);
    //return response
    return redirect()->route('adminpanel.technicians')
        ->with('success', 'Service updated successfully.');
    }



    public function destroy($id)
    {
        Services::findOrFail($id)->delete();
        return back()->with('success', 'Products Deleted');
    }

}
