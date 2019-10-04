<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertybaseLeadDestinationAppendConfigsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('propertybase_lead_destination_append_configs', function (Blueprint $table) {
            
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
        Schema::dropIfExists('propertybase_lead_destination_append_configs');
    }

}
