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
        Schema::create('appnotification', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('uid');
            $table->integer('pid');
            $table->integer('ticketid')->nullable();
            $table->string('message', 100);
            $table->integer('read_by')->default(0);
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
        Schema::dropIfExists('appnotification');
    }
};
