<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsRawTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_raw', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->comment('foreign Key from products');
            $table->integer('size_id')->comment('foreign Key from sizes');
            $table->integer('raw1_id');
            $table->string('raw1_name');
            $table->integer('raw2_id')->nullable();
            $table->string('raw2_name')->nullable();
            $table->string('raw_combination',11)->comment('single or pairs');
            $table->integer('product_per_raws');
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
        Schema::dropIfExists('products_raw');
    }
}
