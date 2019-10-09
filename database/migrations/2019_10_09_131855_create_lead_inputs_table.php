<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadInputsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_inputs', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->unsignedBigInteger('lead_id');
            $table->unsignedBigInteger('mapping_field_id');
            $table->text('value');
            $table->timestamps();

            $table->foreign('lead_id')
                ->references('id')->on('leads')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('mapping_field_id')
                ->references('id')->on('mapping_fields')
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
        Schema::dropIfExists('lead_inputs');
    }

}
