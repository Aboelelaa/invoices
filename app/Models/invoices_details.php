<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class invoices_details extends Model
{
    //
    // protected $guarded = [];
      protected $fillable = [
        'invoice_id',
        'invoice_number',
        'product',
        'Section',
        'Status',
        'Value_Status',
        'note',
        'user',
        'payment_date',
    ];


    public function invoice(){
       return $this->belongsTo(invoices::class);
    }


}
