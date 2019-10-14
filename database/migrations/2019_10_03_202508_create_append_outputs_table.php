<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppendOutputsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('append_outputs', function (Blueprint $table) {

            $table->string('slug', 255)->primary();
            $table->string('name', 255);
            $table->string('description', 255);
            $table->boolean('uses_person_document')->default(false);
            $table->boolean('uses_household_document')->default(false);
            $table->boolean('uses_place_document')->default(false);
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
        Schema::dropIfExists('append_outputs');
    }

}
