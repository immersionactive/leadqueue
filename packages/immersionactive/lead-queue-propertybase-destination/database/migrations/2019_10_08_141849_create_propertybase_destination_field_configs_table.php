<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertybaseDestinationFieldConfigsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('propertybase_destination_field_configs', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->string('contact_field_name', 255);
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
        Schema::dropIfExists('propertybase_destination_field_configs');
    }

}
