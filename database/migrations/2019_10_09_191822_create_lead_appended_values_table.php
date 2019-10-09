<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadAppendedValuesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_appended_values', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->unsignedBigInteger('lead_id');
            $table->unsignedBigInteger('destination_append_id');
            $table->text('value');
            $table->timestamps();

            $table->foreign('lead_id')
                ->references('id')->on('leads')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('destination_append_id')
                ->references('id')->on('destination_appends')
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
        Schema::dropIfExists('lead_appended_values');
    }

}
