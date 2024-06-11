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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('firstname');
            $table->string('lastname', 150)->nullable();
            $table->string('image')->nullable();
            $table->string('companyname')->nullable();
            $table->string('company_address')->nullable();
            $table->string('latitude', 20)->default('0');
            $table->string('longitude', 20)->default('0');
            $table->string('phone', 20);
            $table->integer('userid')->nullable();
            $table->integer('workerid')->nullable();
            $table->string('cardnumber', 50)->nullable();
            $table->string('date', 50)->nullable();
            $table->string('securitycode', 10)->nullable();
            $table->string('accept_terms_conditions', 10)->nullable();
            $table->string('email')->unique();
            $table->string('role', 50)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('wpassword')->nullable();
            $table->decimal('amount', 18)->nullable();
            $table->string('paymenttype', 100)->nullable();
            $table->rememberToken();
            $table->string('openingtime', 50)->nullable();
            $table->string('closingtime', 50)->nullable();
            $table->string('host', 50)->nullable();
            $table->string('smtpusername', 50)->nullable();
            $table->string('smtppassword', 50)->nullable();
            $table->string('currency', 50)->nullable();
            $table->string('timezone', 50)->nullable();
            $table->text('firebase')->nullable();
            $table->text('googleplace')->nullable();
            $table->string('status', 10)->default('Active');
            $table->double('featureprice')->nullable();
            $table->integer('goodproduct')->nullable();
            $table->integer('lowproduct')->nullable();
            $table->string('color', 25)->nullable()->default('#faed61');
            $table->string('txtcolor', 50)->nullable()->default('#000');
            $table->string('taxtype', 20)->nullable();
            $table->string('servicevalue', 50)->nullable();
            $table->string('productvalue', 50)->nullable();
            $table->string('taxvalue', 50)->nullable();
            $table->longText('footercontent')->nullable();
            $table->longText('bodytext')->nullable();
            $table->string('subject')->nullable();
            $table->longText('spublickey')->nullable();
            $table->longText('ssecretkey')->nullable();
            $table->integer('expmonth')->nullable();
            $table->integer('expyear')->nullable();
            $table->string('feature_img')->nullable();
            $table->string('per_day_hours', 50)->default('8');
            $table->string('tab1')->nullable();
            $table->string('tab2')->nullable();
            $table->string('tab3')->nullable();
            $table->string('tab4')->nullable();
            $table->string('tab5')->nullable();
            $table->string('tab1title', 25);
            $table->string('tab2title', 25);
            $table->string('tab3title', 25);
            $table->string('tab4title', 25);
            $table->string('tab5title', 25);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
