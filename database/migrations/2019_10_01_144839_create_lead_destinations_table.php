<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadDestinationsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_destinations', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id');
            $table->string('name', 255);
            $table->boolean('is_active');
            $table->text('notes');
            $table->unsignedBigInteger('destination_config_id')->nullable();
            $table->string('destination_config_type', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('client_id');
            $table->index('destination_config_id');

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
        Schema::dropIfExists('lead_destinations');
    }

}
