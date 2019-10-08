<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGravityFormsSourceFieldConfigsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gravity_forms_source_field_configs', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->string('field_name', 255);
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
        Schema::dropIfExists('gravity_forms_source_field_configs');
    }

}
