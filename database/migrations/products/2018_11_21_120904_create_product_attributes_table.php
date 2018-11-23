<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('cate_id')->unsigned();
            $table->string('attribute_name', 50);
            $table->string('attribute_desc', 500)->nullable();
            $table->tinyInteger('form_type')->default(1)->unsigned();
            $table->tinyInteger('data_type')->default(1)->unsigned();
            $table->string('options', 1000)->nullable();
            $table->string('default', 500)->nullable();
            $table->softDeletes();
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_attributes');
    }
}
