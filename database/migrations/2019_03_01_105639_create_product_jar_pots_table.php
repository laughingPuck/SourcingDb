<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductJarPotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_jarpot', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('cosmopak_item')->nullable();
            $table->string('vendor_item')->nullable();
            $table->string('manufactory_name')->nullable();
            $table->string('item_description')->nullable();
            $table->string('shape')->nullable();
            $table->string('chamber')->nullable();
            $table->decimal('ofc')->nullable();
            $table->decimal('estimate_capacity')->nullable();
            $table->string('color')->nullable();
            $table->string('cap_material')->nullable();
            $table->string('liner_material')->nullable();
            $table->string('base_material')->nullable();
            $table->string('cover_disc')->nullable();
            $table->string('stifter_material')->nullable();
            $table->string('wall_style')->nullable();
            $table->string('closure_mechanism')->nullable();
            $table->decimal('overall_length')->nullable();
            $table->decimal('overall_width')->nullable();
            $table->decimal('overall_height')->nullable();
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
        Schema::dropIfExists('product_jarpot');
    }
}
