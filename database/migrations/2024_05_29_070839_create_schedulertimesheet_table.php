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
        Schema::create('schedulertimesheet', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('workerid');
            $table->integer('ticketid')->nullable();
            $table->string('starttime', 100)->nullable();
            $table->string('endtime', 100)->nullable();
            $table->string('date', 50);
            $table->string('date1', 50)->nullable();
            $table->string('totalhours', 50)->nullable();
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
        Schema::dropIfExists('schedulertimesheet');
    }
};
