<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCovidCasesUsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('covid_cases_us', function (Blueprint $table) {
            //
            $table->id("id");
            $table->char("iso", 2);
            $table->string("state_code", 255);
            $table->bigInteger('confirmed');
            $table->bigInteger('deaths');
            $table->bigInteger('recovered');
            $table->date('date');
            $table->integer('source')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('covid_cases_us');
    }
}
