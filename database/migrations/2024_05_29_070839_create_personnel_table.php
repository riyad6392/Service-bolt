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
        Schema::create('personnel', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('userid');
            $table->integer('workerid')->nullable();
            $table->string('personnelname');
            $table->string('phone', 20);
            $table->string('email');
            $table->string('ticketid');
            $table->string('color', 50)->nullable();
            $table->string('address')->nullable();
            $table->string('latitude', 50)->nullable();
            $table->string('longitude', 50)->nullable();
            $table->string('livelat', 50)->nullable();
            $table->string('livelong', 50)->nullable();
            $table->string('image')->nullable();
            $table->longText('device_token')->nullable();
            $table->boolean('vibration')->default(true);
            $table->boolean('notification')->default(true);
            $table->string('checkstatus', 11)->nullable();
            $table->integer('logout')->default(0);
            $table->longText('imglist')->nullable();
            $table->longText('description')->nullable();
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
        Schema::dropIfExists('personnel');
    }
};
