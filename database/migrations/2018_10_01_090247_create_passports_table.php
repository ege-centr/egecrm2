<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePassportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('passports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('series', 100)->default('');
            $table->string('number', 100)->default('');
            $table->date('birthday')->nullable()->default(null);
            $table->date('issued_date')->nullable()->default(null);
            $table->string('issued_by')->default('');
            $table->string('address')->default('');
            $table->string('code', 100)->default('');
            $table->string('entity_type');
            $table->unsignedInteger('entity_id');
            $table->index(['entity_type', 'entity_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('passports');
    }
}
