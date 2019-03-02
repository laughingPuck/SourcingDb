<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductPencilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_pencil', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('cosmopak_item')->nullable();
            $table->string('vendor_item')->nullable();
            $table->string('manufactory_name')->nullable();
            $table->string('item_description')->nullable();
            $table->string('use')->nullable();
            $table->string('barrel_material')->nullable();
            $table->string('barrel_shape')->nullable();
            $table->string('sharpenable')->nullable();
            $table->string('formula')->nullable();
            $table->string('formula_texture')->nullable();
            $table->string('formula_description')->nullable();
            $table->decimal('estimate_capacity')->nullable();
            $table->decimal('overall_length')->nullable();
            $table->string('overall_diameter')->nullable();
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
        Schema::dropIfExists('product_pencil');
    }
}
