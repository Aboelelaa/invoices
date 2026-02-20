<?php

namespace App\Exports;

use App\Models\invoices;
use Maatwebsite\Excel\Concerns\FromCollection;

class invoicesexport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return invoices::all();   //لو عايز اعرض كل حاجة

        // return invoices::select('invoice_date','product')->get();    //لوعايز اعرض كولومز معينة
    }
}
