<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class invoices extends Model
{
    use SoftDeletes;
    //
    protected $guarded = [];



    public function details(){
        $this->hasMany(invoices_details::class);
    }

    public function section(){
      return  $this->belongsTo(sections::class);
    }

}
