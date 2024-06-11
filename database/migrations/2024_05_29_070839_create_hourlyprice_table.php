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
        Schema::create('hourlyprice', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('ticketid');
            $table->integer('serviceid');
            $table->integer('hour')->nullable();
            $table->integer('minute')->nullable();
            $table->integer('price')->nullable();
            $table->longText('servicedescription')->nullable();
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
        Schema::dropIfExists('hourlyprice');
    }
};
