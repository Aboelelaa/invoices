<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections=sections::all();
        $products=products::all();
        return view('products.products',compact('sections','products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $validatadata=$request->validate([
        //      'product_name'=>'required|max:255',
        // ]);
        products::create([
                        'product_name' => $request->product_name,
                        'description'  => $request->description,
                        'section_id'   => $request->section_id,
                    ]);
        session()->flash('Add','تم اضافة المنتج بنجاح');
         return Redirect('/products');

        
    }

    /**
     * Display the specified resource.
     */
    public function show(products $products)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(products $products)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // $id=sections::where('section_name',$request->section_name)->first()->id;
        // $row=$request->pro_id;
        // $product=products::findorfail($row);
        // $product->update([
        //                 'product_name' => $request->product_name,
        //                 'section_name'=>$request->section_name,
        //                 'description'  => $request->description,
        //                 'section_id'   => $request->$id
        // ]);
        //  session()->flash('Edit','تم تعديل المنتج بنجاح');
        //  return back();
       
 
    $request->validate([
        'product_name' => 'required',
        'description'  => 'required',
        'section_id'   => 'required|exists:sections,id',
    ]);

    $product = products::findOrFail($request->pro_id);

    $product->update([
        'product_name' => $request->product_name,
        'description'  => $request->description,
        'section_id'   => $request->section_id,
    ]);

    session()->flash('Edit', 'تم تعديل المنتج بنجاح');
    return back();





        // dd($request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
       $id=$request->pro_id;
       $product=products::findorfail($id);
       $product->delete();
        // session()->flash('delete','تم حذف المنتج بنجاح');
       return back()->with('delete','تم حذف المنتج بنجاح');
    
    }
}
