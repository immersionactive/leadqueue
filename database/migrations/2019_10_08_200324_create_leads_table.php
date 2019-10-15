<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {

            $table->bigIncrements('id');            
            $table->unsignedBigInteger('mapping_id');
            $table->unsignedBigInteger('lead_source_request_id');
            $table->enum('status', ['new', 'appended', 'complete', 'append_failed', 'destination_failed'])->default('new');
            $table->unsignedSmallInteger('failed_append_attempts')->default(0);
            $table->unsignedSmallInteger('failed_destination_attempts')->default(0);
            $table->dateTime('appended_at')->nullable();
            $table->dateTime('destination_at')->nullable();
            $table->string('destination_id', 255)->nullable();
            $table->timestamps();

            $table->foreign('mapping_id')
                ->references('id')->on('mappings')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('lead_source_request_id')
                ->references('id')->on('lead_source_requests')
                ->onUpdate('cascade')
                ->onDelete('restrict');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leads');
    }

}
