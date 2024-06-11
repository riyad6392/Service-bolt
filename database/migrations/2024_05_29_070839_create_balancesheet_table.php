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
        Schema::create('balancesheet', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('userid');
            $table->integer('workerid')->nullable();
            $table->integer('ticketid')->nullable();
            $table->decimal('amount', 18);
            $table->string('paymentmethod', 50);
            $table->string('customername', 50);
            $table->string('status', 50);
            $table->integer('is_delete')->default(0);
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
        Schema::dropIfExists('balancesheet');
    }
};
