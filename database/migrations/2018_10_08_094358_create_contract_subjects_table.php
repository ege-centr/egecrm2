<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_subjects', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('contract_id');
            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');

            $table->smallInteger('subject_id')->unsigned();

            $table->smallInteger('lessons')->unsigned()->nullable();
            $table->smallInteger('lessons_planned')->unsigned()->nullable();

            $table->enum('status', ['active', 'to_be_terminated', 'terminated'])->default('active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contract_subjects');
    }
}
