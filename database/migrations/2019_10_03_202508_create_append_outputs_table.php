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
            $table->string('label', 255)->comment('The human-readable name of the property.');
            $table->string('bundle', 255)->comment('The name of the bundle which contains this property in the response from the USADATA API.');
            $table->string('property', 255)->comment('The name of the property itself in the response from the USADATA API.');
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
