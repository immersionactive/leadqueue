<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMappingsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mappings', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id');
            $table->string('name', 255);
            $table->unsignedBigInteger('lead_source_id');
            $table->unsignedBigInteger('lead_destination_id');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('client_id')
                ->references('id')->on('clients')
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
        Schema::dropIfExists('mappings');
    }

}
