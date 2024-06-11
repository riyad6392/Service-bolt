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
        Schema::create('paymentsetting', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('uid');
            $table->integer('pid')->nullable();
            $table->string('paymentbase', 50)->nullable();
            $table->json('content')->nullable();
            $table->string('allspvalue', 50)->nullable();
            $table->json('contentcommission')->nullable();
            $table->string('type', 50)->nullable();
            $table->date('hiredate')->nullable();
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
        Schema::dropIfExists('paymentsetting');
    }
};
