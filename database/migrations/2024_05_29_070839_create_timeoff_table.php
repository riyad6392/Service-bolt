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
        Schema::create('timeoff', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('workerid');
            $table->integer('userid')->nullable();
            $table->string('date', 100);
            $table->string('date1', 50)->nullable();
            $table->string('notes');
            $table->string('reason')->nullable();
            $table->string('submitted_by', 20)->nullable();
            $table->string('status', 50)->nullable();
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
        Schema::dropIfExists('timeoff');
    }
};
