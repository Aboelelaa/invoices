<?php

namespace App\Http\Controllers;

use App\Exports\invoicesexport;
use App\Models\invoices;
use App\Models\invoices_attachments;
use App\Models\invoices_details;
use App\Models\sections;
use App\Models\User;
use App\Notifications\Add_invoice;
use App\Notifications\Add_invoiceNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 
        $invoices=invoices::All();

        return view('invoices.invoices',compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $sections=sections::all();
        return view('invoices.add_invoice',compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
       
       $invoice= invoices::create([
           'invoice_number'=>$request->invoice_number,
           'invoice_date'=>$request->invoice_Date,
           'due_data'=>$request->Due_date,
           'section_id'=>$request->Section,
           'product'=>$request->product,
           'amount_collection'=>$request->Amount_collection,
           'amount_commission'=>$request->Amount_Commission,
           'discount'=>$request->Discount,
           'rate_vat'=>$request->Rate_VAT,
           'value_vat'=>$request->Value_VAT,
         
           'total'=>$request->Total,
           'note'=>$request->note,
           'status'=>'غير مدفوعة',
           'value_status'=>2
        ]);
  
   

        $invoice_id=invoices::latest()->first()->id;
        invoices_details::create([
            'invoice_number'=>$request->invoice_number,
            'invoice_id'=>$invoice_id,
            'product'=>$request->product,
          'Section'=>$request->Section,    
            'Status'=>'غير مدفوعة',
            'Value_Status'=>2,
            'note'=>$request->note,
            'user'=>(Auth::user()->name)
            
        ]);



 $request->validate([
    'invoice_number' => 'required|string',
    'pic' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
]);

        if($request->hasFile('pic')){
            $invoice_number=$request->invoice_number;
            $invoice_id=invoices::latest()->first()->id;
            $image=$request->file('pic');
            $file_name=$image->getClientOriginalName();
        }
        $attachments=new invoices_attachments();
        $attachments->file_name=$file_name;
        $attachments->invoice_number=$invoice_number;
        $attachments->invoice_id=$invoice_id;
        $attachments->created_by=Auth::user()->name;
        $attachments->save();

        //move pic

        $image_name=$request->pic->getClientOriginalName();
        $request->pic->move(public_path('attachments/'.$invoice_number),$image_name);

          


// $invoice->details()->create([
//     'invoice_number' => $request->invoice_number,
//         'invoice_id'     => $invoice->id,
//         'product'        => $request->product,
//         'Section'     => $request->Section,
//         'Status'         => 'غير مدفوعة',
//         'Value_Status'   => 2,
//         'note'           => $request->note,
//         'user'           => Auth::user()->name,
// ]);

//mail

$user=User::get();    //لوعايز يوصل اشعار للادمن و اليوصل اللى ضاف فاتورة
// $user=User::findorfail(Auth::user()->id);   //لوعايز يوصل اشعار للى ضاف فاتورة بس

Notification::send($user,new Add_invoice($invoice_id));
// $invoice_id=invoices::latest()->first();
// $user->notify(new Add_invoiceNotification($invoice_id));
Notification::send($user,new Add_invoiceNotification($invoice_id));




   session()->flash('Add','تم اضافة الفاتورة بنجاح');

        return redirect()->route("invoiceindex");


    // return $request;
    }

    /**
     * Display the specified resource.
     */
    public function show(invoices $invoices)
    {
        //
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $id)
    {
        //
        $invoices=invoices::where('id',$id)->first();
        $sections=sections::all();
        return view('invoices.edit_invoice',compact('invoices','sections'));
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
        $id=$request->invoice_id;
        $invoice=invoices::findorfail($id);
        $invoice->update([
            'invoice_number'=>$request->invoice_number,
            'invoice_date'=>$request->invoice_date,
            'due_data'=>$request->due_data,
            'product'=>$request->product,
            'section_id'=>$request->Section,
            'amount_collection'=>$request->amount_collection,
            'amount_commission'=>$request->amount_commission,
            'discount'=>$request->discount,
            'rate_vat'=>$request->rate_vat,
            'value_vat'=>$request->value_vat,
            'total'=>$request->total,
            'note'=>$request->note
        ]);

        return back()->with('edit','تم تعديل الفاتورة بنجاح');
        

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
         $id=$request->invoice_id;
         $invoice=invoices::findorfail($id);
        // $invoice=invoices::where('id',$id)->first();
        $attachments=invoices_attachments::where('invoice_id',$id)->get();
        $id_page=$request->id_page;
        if($id_page!=2){
        
        foreach($attachments as $attachment){
            // $path=$attachment->invoice_number.'/'.$attachment->file_name;
            $path = $attachment['invoice_number'].'/'.$attachment['file_name'];
            if(storage::disk('public_uploads')->exists($path)){
                storage::disk('public_uploads')->delete($path);
            }
        }
        
           $invoice->forcedelete();
        //  $invoice->delete();
         session()->flash('delete_invoice');

         return redirect('/invoices');
        
        }else{

        //  $invoice->forcedelete();
         $invoice->delete();
         session()->flash('archive_invoice');

         return redirect()->route('invoices_archive');
    }

    }

    public function Status_show($id){
        $invoices=invoices::findorfail($id);
        return view('invoices.status_update',compact('invoices'));

    }

    public function Status_Update(Request $request,$id){
        $invoices=invoices::findorfail($id);

        if($request->status==='مدفوعة'){
             $invoices->update([
                'status'=>$request->status,      
                'value_status'=>1,      
                'payment_date'=>$request->payment_date      
             ]);

             invoices_details::create([
                'invoice_id'=>$request->invoice_id,
                'invoice_number'=>$request->invoice_number,
                'product'=>$request->product,
                'Section'=>$request->Section,
                'Status'=>$request->status,
                'Value_Status'=>1,
                'note'=>$request->note,
                'payment_date'=>$request->payment_date,
                'user'=>(Auth::user()->name)
             ]);
        }elseif($request->status==='غير مدفوعة'){
             $invoices->update([
                'status'=>$request->status,      
                'value_status'=>2,      
                'payment_date'=>$request->payment_date      
             ]);

             invoices_details::create([
                'invoice_id'=>$request->invoice_id,
                'invoice_number'=>$request->invoice_number,
                'product'=>$request->product,
                'Section'=>$request->Section,
                'Status'=>$request->status,
                'Value_Status'=>2,
                'note'=>$request->note,
                'payment_date'=>$request->payment_date,
                'user'=>(Auth::user()->name)
             ]);
        }
        
        
        else{
             $invoices->update([
                'status'=>$request->status,      
                'value_status'=>3,      
                'payment_date'=>$request->payment_date      
             ]);

             invoices_details::create([
                'invoice_id'=>$request->invoice_id,
                'invoice_number'=>$request->invoice_number,
                'product'=>$request->product,
                'Section'=>$request->Section,
                'Status'=>$request->status,
                'Value_Status'=>3,
                'note'=>$request->note,
                'payment_date'=>$request->payment_date,
                'user'=>(Auth::user()->name)
             ]);
        }

         session()->flash('Status_Update');
        return redirect('/invoices');

        }
    

        public function invoices_paid(){
            // $invoices=DB::table('invoices')->where('value_status',1)->get();
            $invoices=invoices::where('status','مدفوعة')->get();
            // $invoices=invoices::where('value_status',1)->get();
            return view('invoices.invoices_paid',compact('invoices'));            
        }

         public function invoices_unpaid(){
            // $invoices=DB::table('invoices')->where('value_status',1)->get();
            $invoices=invoices::where('status','غير مدفوعة')->get();
            // $invoices=invoices::where('value_status',2)->get();
            return view('invoices.invoices_unpaid',compact('invoices'));            
        }

         public function invoices_partial(){
            // $invoices=DB::table('invoices')->where('value_status',1)->get();
            $invoices=invoices::where('Value_Status',3)->get();
            // $invoices=invoices::where('value_status',3)->get();
            return view('invoices.invoices_partial',compact('invoices'));            
        }

        public function Print_invoice($id  ){
         $invoices=invoices::with('section')->findOrFail($id);
        //  $invoices=invoices::findOrFail($id)->first();

         session()->flash('invoice_print','تم طباعة الفاتورة بنجاح');

        return view('invoices.print_invoice',compact('invoices'));

        }


        public function export_invoices(){
            return Excel::download(new invoicesexport,'invoices.xlsx');
        }
        public function markasreadaall(){
         $userunread=Auth::user()->unreadNotifications;
         if($userunread){
            $userunread->markAsRead();
             return back();
         }
        }


        public function markasread($id){
            $notification=auth::user()->unreadNotifications->where('id', $id)->first();
            if($notification){
                $notification->markAsRead();
            }
            return back();
        }
      






}