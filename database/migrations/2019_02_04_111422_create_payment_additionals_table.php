<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentAdditionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_additionals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('entity_type');
            $table->unsignedInteger('entity_id');
            $table->index(['entity_type', 'entity_id']);
            $table->integer('sum');
            $table->string('purpose');
            $table->date('date');
            $table->smallInteger('year')->unsigned();
            $table->integer('created_admin_id')->unsigned()->nullable();
            $table->foreign('created_admin_id')->references('id')->on('admins')->onDelete('set null');
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
        Schema::dropIfExists('payment_additionals');
    }
}
