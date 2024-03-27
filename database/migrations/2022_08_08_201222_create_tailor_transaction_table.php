<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTailorTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tailor_transaction', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('tailor_id')->comment('foreign Key from tailors');
            $table->integer('product_id')->comment('foreign Key from products');
            $table->string('product_name');
            $table->integer('size_id');
            $table->string('size_name');
            $table->integer('products_raw_id');
            $table->string('raw_name');
            $table->string('raw_id');
            $table->integer('raw_qty');
            $table->integer('total_product_qty');
            $table->string('description',50)->nullable();
            $table->integer('out_qty');
            $table->integer('in_qty');
            $table->integer('left_qty');
            $table->softDeletes();
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
        Schema::dropIfExists('tailor_transaction');
    }
}
