<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesExtendedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities_extended', function (Blueprint $table) {
            //
            $table->string("city", 50);
            $table->string("state_code", 2);
            $table->integer("zip")->nullable();
            $table->double("latitude")->nullable();
            $table->double("longitude")->nullable();
            $table->string("county", 50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('cities_extended');
    }
}
