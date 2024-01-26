<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_transactions', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('supplier_id')->comment('foreign Key from suppliers');
            $table->integer('raw_id')->comment('foreign Key from raws');
            $table->integer('qty_pkg');
            $table->integer('qty');
            $table->integer('price');
            $table->integer('total_amount');
            $table->softDeletes();
            $table->integer('created_emp')->default(1);
            $table->integer('updated_emp')->default(1);
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
        Schema::dropIfExists('supplier_transactions');
    }
}
