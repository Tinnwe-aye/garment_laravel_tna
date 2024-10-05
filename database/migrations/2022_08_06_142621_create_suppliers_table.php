<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name_mm',50);
            $table->string('name_en',50);
            $table->string('phone_no')->length(15);
            $table->string('email',50)->nullable();
            $table->string('businessName')->nullable();
            $table->string('address')->nullable();
            $table->string('description')->nullable();
            $table->softDeletes();
            $table->integer('created_emp')->nullable();
            $table->integer('updated_emp')->nullable();
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
        Schema::dropIfExists('suppliers');
    }
}
