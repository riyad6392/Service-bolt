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
        Schema::create('quote', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('userid');
            $table->integer('workerid')->nullable();
            $table->integer('parentid')->nullable()->default(0);
            $table->integer('customerid');
            $table->string('customername');
            $table->integer('address_id')->nullable();
            $table->string('address')->nullable();
            $table->string('latitude', 50)->nullable();
            $table->string('longitude', 50)->nullable();
            $table->string('serviceid', 50)->nullable();
            $table->string('servicename')->nullable();
            $table->string('product_id', 50)->nullable();
            $table->string('product_name')->nullable();
            $table->integer('personnelid')->nullable();
            $table->string('radiogroup', 50);
            $table->string('frequency', 100);
            $table->string('time', 50)->nullable();
            $table->string('minute', 50)->nullable();
            $table->string('giventime', 50)->nullable();
            $table->string('givenendtime', 20)->nullable();
            $table->string('givendate', 50)->nullable();
            $table->date('givenstartdate')->nullable();
            $table->date('givenenddate')->nullable();
            $table->decimal('price', 18);
            $table->decimal('amount_paid', 18)->default(0);
            $table->decimal('over_paid', 18)->default(0);
            $table->integer('partial')->default(0);
            $table->decimal('tickettotal', 18)->nullable();
            $table->decimal('tax', 18)->nullable();
            $table->string('etc', 50)->nullable();
            $table->string('description')->nullable();
            $table->string('customer_notes')->nullable();
            $table->string('internal_notes')->nullable();
            $table->integer('ticket_status')->default(0)->comment('1-not assign, 2-assign, 4-picked, 3-completed');
            $table->string('checklist', 100)->nullable();
            $table->longText('note_for_customer')->nullable();
            $table->longText('note_for_admin')->nullable();
            $table->text('imagelist')->nullable();
            $table->string('payment_status', 50)->nullable();
            $table->integer('invoiceid')->nullable();
            $table->string('payment_mode', 50)->nullable();
            $table->integer('checknumber')->nullable();
            $table->integer('primaryname')->nullable();
            $table->decimal('payment_amount', 18)->nullable();
            $table->date('ticketdate')->nullable();
            $table->date('duedate')->nullable();
            $table->tinyInteger('invoiced')->default(0);
            $table->string('invoicenote')->nullable();
            $table->integer('card_number')->nullable();
            $table->integer('expiration_date')->nullable();
            $table->integer('cvv')->nullable();
            $table->integer('flag')->default(1);
            $table->integer('count')->default(0);
            $table->date('ticket_created_date')->nullable();
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
        Schema::dropIfExists('quote');
    }
};
