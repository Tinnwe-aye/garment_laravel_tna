<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTailorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tailors', function (Blueprint $table) {
            $table->id();
            $table->integer('tailor_id');
            $table->string('name_mm');
            $table->string('name_en');
            $table->string('phone_no')->length(15);
            $table->string('nrc_no');
            $table->string('address');
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
        Schema::dropIfExists('tailors');
    }
}
