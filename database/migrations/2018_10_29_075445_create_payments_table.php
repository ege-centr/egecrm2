<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sum');
            $table->date('date');
            $table->smallInteger('year')->unsigned()->index();
            $table->enum('method', ['card', 'cash', 'bill', 'card_online'])->nullable();
            $table->enum('type', ['payment', 'return'])->nullable();
            $table->enum('category', ['study', 'career_guidance', 'ege_trial'])->nullable();
            $table->string('card_first_digit', 1);
            $table->string('card_last_digits', 4);
            $table->boolean('is_confirmed')->default(false);

            $table->integer('created_admin_id')->unsigned()->nullable();
            $table->foreign('created_admin_id')->references('id')->on('admins')->onDelete('set null');

            $table->string('entity_type');
            $table->unsignedInteger('entity_id');
            $table->index(['entity_type', 'entity_id']);

            $table->unsignedInteger('bill_number')->nullable();

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
        Schema::dropIfExists('payments');
    }
}
