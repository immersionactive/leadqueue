<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertybaseWebtoprospectDestinationConfigsTable extends Migration
{
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('propertybase_webtoprospect_destination_configs', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->string('account', 255);
            $table->string('token', 255);

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('propertybase_webtoprospect_destination_configs');
    }

}
