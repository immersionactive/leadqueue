<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMappingFieldsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('mapping_fields', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mapping_id');
            $table->string('name', 255);
            $table->unsignedBigInteger('source_field_config_id');
            $table->string('source_field_config_type', 255);
            $table->unsignedBigInteger('destination_field_config_id');
            $table->string('destination_field_config_type', 255);
            $table->string('append_input_property', 255)->nullable();
            $table->timestamps();

            $table->foreign('mapping_id')
                ->references('id')->on('mappings')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('append_input_property')
                ->references('property')->on('append_inputs')
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
        Schema::dropIfExists('mapping_fields');
    }

}
