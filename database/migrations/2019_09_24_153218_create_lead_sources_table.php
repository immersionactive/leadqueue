<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_sources', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id');
            $table->string('name', 255);
            $table->boolean('is_active');
            $table->text('notes');
            $table->unsignedBigInteger('source_config_id')->nullable();
            $table->string('source_config_type', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('client_id');
            $table->index('source_config_id');

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
        Schema::dropIfExists('lead_sources');
    }
}
