<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfusionsoftDestinationConfigsTable extends Migration
{

    public function up()
    {
        Schema::create('infusionsoft_destination_configs', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('client_id', 255);
            $table->string('client_secret', 255);
            $table->string('access_token', 255)->nullable();
            $table->dateTime('access_token_expires_at')->nullable();
            $table->string('refresh_token', 255)->nullable();
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('infusionsoft_destination_configs');
    }

}
