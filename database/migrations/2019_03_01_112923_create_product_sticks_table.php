<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSticksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_stick', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('cosmopak_item')->nullable();
            $table->string('vendor_item')->nullable();
            $table->string('manufactory_name')->nullable();
            $table->string('item_description')->nullable();
            $table->string('material')->nullable();
            $table->string('shape')->nullable();
            $table->string('edges_style')->nullable();
            $table->string('cup')->nullable();
            $table->integer('cup_size')->nullable();
            $table->string('cup_material')->nullable();
            $table->string('recommend_for_soft')->nullable();
            $table->decimal('estimate_capacity')->nullable();
            $table->string('cup_cover_material')->nullable();
            $table->string('cap_material')->nullable();
            $table->string('ashell_material')->nullable();
            $table->string('body_material')->nullable();
            $table->string('outer_base_material')->nullable();
            $table->decimal('overall_length')->nullable();
            $table->decimal('overall_width')->nullable();
            $table->decimal('overall_height')->nullable();
            $table->string('filling_method')->nullable();
            $table->string('closure_mechanism')->nullable();
            $table->string('mechanism')->nullable();
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
        Schema::dropIfExists('product_stick');
    }
}
