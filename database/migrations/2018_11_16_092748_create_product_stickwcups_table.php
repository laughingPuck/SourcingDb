<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductStickwcupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_stickwcup', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('cosmopak_item');
            $table->string('vendor_item');
            $table->string('manufactory_name');
            $table->string('item_description');
            $table->string('material');
            $table->string('shape');
            $table->string('style');
            $table->string('cup');
            $table->decimal('cup_size');
            $table->string('cover_material');
            $table->decimal('overall_length');
            $table->decimal('overall_width');
            $table->decimal('overall_height');
            $table->string('mechanism');
            $table->string('storage_location');
            $table->string('sample_available');
            $table->string('related_projects');
            $table->string('moq');
            $table->decimal('price');
            $table->string('mold_status');
            $table->tinyInteger('state')->unsigned();
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
        Schema::dropIfExists('product_stickwcup');
    }
}
