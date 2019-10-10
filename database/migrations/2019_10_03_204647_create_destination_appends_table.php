<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDestinationAppendsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('destination_appends', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->unsignedBigInteger('lead_destination_id');
            $table->string('append_output_path', 255);
            $table->boolean('is_enabled')->default(true);
            $table->unsignedBigInteger('destination_append_config_id');
            $table->string('destination_append_config_type', 255);
            $table->timestamps();

            $table->foreign('append_output_path')
                ->references('path')->on('append_outputs')
                ->onUpdate('cascade')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('destination_appends');
    }

}
