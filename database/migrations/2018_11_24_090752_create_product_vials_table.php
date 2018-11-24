<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductVialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_vial', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('cosmopak_item')->nullable();
            $table->string('vendor_item')->nullable();
            $table->string('manufactory_name')->nullable();
            $table->string('item_description')->nullable();
            $table->string('cap_material')->nullable();
            $table->string('base_material')->nullable();
            $table->string('stem_material')->nullable();
            $table->string('shape')->nullable();
            $table->string('vial')->nullable();
            $table->decimal('overall_length')->nullable();
            $table->decimal('overall_width')->nullable();
            $table->decimal('overall_height')->nullable();
            $table->string('collar')->nullable();
            $table->string('storage_location')->nullable();
            $table->string('sample_available')->nullable();
            $table->string('related_projects')->nullable();
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
        Schema::dropIfExists('product_vial');
    }
}
