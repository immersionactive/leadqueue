<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->unsignedBigInteger('lead_source_id');
            // $table->enum('status', ['', '']);
            $table->text('request_url');
            $table->string('request_origin_ip', 15);
            $table->string('request_method', 255);
            $table->text('request_headers_json');
            $table->string('request_content_type', 255)->nullable();
            $table->text('request_body_raw');
            $table->text('request_body_json');
            $table->timestamps();

            $table->foreign('lead_source_id')
                ->references('id')->on('lead_sources')
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
        Schema::dropIfExists('leads');
    }

}
