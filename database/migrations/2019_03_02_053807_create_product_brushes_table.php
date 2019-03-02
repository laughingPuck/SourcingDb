<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductBrushesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_brush', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('cosmopak_item')->nullable();
            $table->string('vendor_item')->nullable();
            $table->string('manufactory_name')->nullable();
            $table->string('item_description')->nullable();
            $table->string('cap_material')->nullable();
            $table->string('handle_material')->nullable();
            $table->string('handle_shape')->nullable();
            $table->decimal('handle_length')->nullable();
            $table->string('brush_material')->nullable();
            $table->string('brush_shape')->nullable();
            $table->string('brush')->nullable();
            $table->string('ferrual_material')->nullable();
            $table->decimal('ferrual_length')->nullable();
            $table->decimal('overall_length')->nullable();
            $table->decimal('overall_width')->nullable();
            $table->string('set_individual')->nullable();
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
        Schema::dropIfExists('product_brush');
    }
}
