<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductInDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_in_data', function (Blueprint $table) {
            $table->id();
            $table->integer('product_in_id');
            $table->integer('product_id');
            $table->string('product_name',255);
            $table->integer('size_id');
            $table->string('size_name',45);
            $table->integer('price');
            $table->integer('qty');
            $table->integer('amount');
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
        Schema::dropIfExists('product_in_data');
    }
}
