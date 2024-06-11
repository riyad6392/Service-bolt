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
        Schema::create('customer', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('userid');
            $table->integer('workerid')->nullable();
            $table->string('customername', 200);
            $table->string('term_name')->nullable();
            $table->string('phonenumber', 20);
            $table->string('email')->nullable();
            $table->string('companyname', 200)->nullable();
            $table->string('billingaddress')->nullable();
            $table->string('mailingaddress')->nullable();
            $table->string('serviceid', 50)->nullable();
            $table->string('productid', 100)->nullable();
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
        Schema::dropIfExists('customer');
    }
};
