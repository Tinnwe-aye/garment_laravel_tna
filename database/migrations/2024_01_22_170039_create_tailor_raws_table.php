<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTailorRawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tailor_raws', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('tailor_id');
            $table->integer('products_raw_id');
            $table->integer('raw_qty');
            $table->integer('total_product_qty');
            $table->string('description',50)->nullable();
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
        Schema::dropIfExists('tailor_raws');
    }
}
