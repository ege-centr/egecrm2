<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');

            $table->string('student_first_name');
            $table->string('student_last_name');
            $table->string('student_middle_name');

            $table->string('representative_first_name');
            $table->string('representative_last_name');
            $table->string('representative_middle_name');

            $table->unsignedInteger('head_tutor_id')->nullable()->default(null);
            $table->unsignedTinyInteger('grade')->nullable()->default(null);
            $table->unsignedSmallInteger('year')->nullable()->default(null);
            $table->string('branches');

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
        Schema::dropIfExists('clients');
    }
}
