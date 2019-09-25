<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebflowSourceConfigsTable extends Migration
{
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webflow_source_configs', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->string('webflow_site_id', 255);
            $table->string('webflow_form_name', 255)->nullable();
            
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('webflow_source_configs');
    }

}
