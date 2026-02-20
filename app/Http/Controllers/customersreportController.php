<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use App\Models\sections;
use Illuminate\Http\Request;
use Carbon\Carbon;
class customersreportController extends Controller
{
    //
    public function index(){
        $sections=sections::all();
        return view('reports.customers_report',compact('sections'));
    }

    public function Search_customers(Request $request){
        if($request->Section && $request->product && $request->start_at=='' && $request->end_at==''){
            $invoices=invoices::where('section_id','=',$request->Section)->where('product','=',$request->product)->get();
            $sections=sections::all();
            return view('reports.customers_report',compact('sections','invoices'));
        }else{
            $start_at=Carbon::parse($request->start_at)->startOfDay();
            $end_at= Carbon::parse($request->end_at)->endOfDay();
            $invoices=invoices::where('section_id','=',$request->Section)->where('product','=',$request->product)->wherebetween('invoice_date',[$start_at,$end_at])->get();
            $sections=sections::all();
            return view('reports.customers_report',compact('sections','invoices','start_at','end_at'));
        }
        
    }


}
