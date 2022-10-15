<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_transaction', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('supplier_id')->comment('foreign Key from suppliers');
            $table->integer('raw_id')->comment('foreign Key from raws');
            $table->integer('qty_pack');
            $table->integer('qty');
            $table->integer('price');
            $table->integer('tota_amt');
            $table->softDeletes();
            $table->integer('created_emp');
            $table->integer('updated_emp');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_transaction');
    }
}
