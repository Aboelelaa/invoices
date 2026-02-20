<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use App\Models\invoices_attachments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoicesAttachmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
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

       $request->validate([
        'file_name'=>'mimes:pdf,png,jpg,jpeg'
      ],[
         'file_name'=>' pdf,png,jpg,jpeg صيغة المرفق يجب ان تكون'   
      ]
      );






        $image=$request->file('file_name');
        $file_name=$image->getClientOriginalName();
        $invoice_number=$request->invoice_number;
        $invoice_id=$request->invoice_id;

        $attachments=new invoices_attachments();
        $attachments->file_name=$file_name;
        $attachments->invoice_number=$invoice_number;
        $attachments->invoice_id=$invoice_id;
        $attachments->created_by=Auth::user()->name;
        $attachments->save();

        $image_name=$request->file_name->getClientOriginalName();
        $request->file_name->move(public_path('attachments/'.$invoice_number),$image_name);

        return back()->with('Add','تم اضافة المرفق بنجاح');
        
    }

    /**
     * Display the specified resource.
     */
    public function show(invoices_attachments $invoices_attachments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(invoices_attachments $invoices_attachments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, invoices_attachments $invoices_attachments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(invoices_attachments $invoices_attachments)
    {
        //
    }
}
