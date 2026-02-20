<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->string('invoice_number');
          $table->date('invoice_date')->nullable();
          $table->date('due_data')->nullable();   //تاريخ الاستحقاق
          $table->string('product');   //القسم
          $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');
          $table->decimal('amount_collection',8,2)->nullable();  //مبلغ التحصيل
          $table->decimal('amount_commission',8,2);  //مبلغ العمولة
          $table->decimal('discount',8,2);            //الخصم
          $table->decimal('value_vat',8,2);    //قيمة الضريبة
          $table->string('rate_vat');   //نسبة الضريبة
          $table->decimal('total',8,2);    //المبلغ النهائي
          $table->string('status',50);         //حالة الفاتورة مدفوعة ولا لا
          $table->integer('value_status');   //زي الفاتورة المدفوعة تساوى واحد والغير مدفوعة تسوى زيرو هتساعد لما اعمل سيليكت
          $table->text('note')->nullable();
          $table->date('payment_date')->nullable();
          $table->softDeletes();
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};