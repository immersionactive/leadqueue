<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppendOutputsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('append_outputs', function (Blueprint $table) {

            $table->string('path', 255)->primary()->comment('The path to the element, in the format document.bundle.element (e.g., "person.basicdemographics.age").');
            $table->string('label', 255)->comment('The human-readable name of the element.');
            // $table->enum('translator', ['yesno'])->nullable()->default(null);
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
        Schema::dropIfExists('append_outputs');
    }

}
