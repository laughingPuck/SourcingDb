<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductCompactpalettesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_compactpalette', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('cosmopak_item')->nullable();
            $table->string('vendor_item')->nullable();
            $table->string('manufactory_name')->nullable();
            $table->string('item_description')->nullable();
            $table->string('material')->nullable();
            $table->string('shape')->nullable();
            $table->string('pan_well')->nullable();
            $table->decimal('overall_length')->nullable();
            $table->decimal('overall_width')->nullable();
            $table->decimal('overall_height')->nullable();
            $table->tinyInteger('mirror')->unsigned()->nullable();
            $table->tinyInteger('window')->unsigned()->nullable();
            $table->string('pan_well_shape')->nullable();
            $table->decimal('pan_well_width')->nullable();
            $table->decimal('pan_well_height')->nullable();
            $table->tinyInteger('applicator_well')->unsigned()->nullable();
            $table->tinyInteger('injector_pin')->unsigned()->nullable();
            $table->string('latch_system')->nullable();
            $table->string('storage_location')->nullable();
            $table->string('sample_available')->nullable();
            $table->string('related_projects')->nullable();
            $table->string('moq')->nullable();
            $table->decimal('price')->nullable();
            $table->tinyInteger('state')->default(1)->unsigned();
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
        Schema::dropIfExists('product_compactpalette');
    }
}
