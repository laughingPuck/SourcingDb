<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTubesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_tube', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('cosmopak_item')->nullable();
            $table->string('vendor_item')->nullable();
            $table->string('manufactory_name')->nullable();
            $table->string('item_description')->nullable();
            $table->string('tube_shape')->nullable();
            $table->string('chamber')->nullable();
            $table->string('layer')->nullable();
            $table->string('tube_material')->nullable();
            $table->string('cap_material')->nullable();
            $table->string('closure_mechanism')->nullable();
            $table->string('applicator')->nullable();
            $table->string('applicator_material')->nullable();
            $table->decimal('estimate_capacity')->nullable();
            $table->decimal('tube_diameter')->nullable();
            $table->decimal('overall_length')->nullable();
            $table->string('moq')->nullable();
            $table->decimal('price')->nullable();
            $table->string('mold_status')->nullable();
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
        Schema::dropIfExists('product_tube');
    }
}
