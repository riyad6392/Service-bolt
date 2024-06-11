<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->integer('workerid')->nullable();
            $table->string('productname');
            $table->string('serviceid', 100)->nullable();
            $table->string('quantity', 100)->nullable();
            $table->string('pquantity', 100)->nullable();
            $table->string('sku', 100)->nullable();
            $table->string('unit')->nullable();
            $table->decimal('price', 18);
            $table->string('category', 100)->nullable();
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
