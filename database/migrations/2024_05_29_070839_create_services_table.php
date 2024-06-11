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
        Schema::create('services', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('userid');
            $table->integer('workerid')->nullable();
            $table->string('servicename', 100);
            $table->decimal('price', 18);
            $table->decimal('preferred', 18)->nullable();
            $table->decimal('overtime', 18)->nullable();
            $table->string('productid', 100)->nullable();
            $table->string('type', 20);
            $table->string('frequency', 100)->nullable();
            $table->string('time', 100)->nullable()->default('0 Hours');
            $table->string('minute', 50)->nullable()->default('0 Minutes');
            $table->string('image')->nullable();
            $table->string('checklist', 100)->nullable();
            $table->string('color', 50)->nullable();
            $table->longText('description')->nullable();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
};
