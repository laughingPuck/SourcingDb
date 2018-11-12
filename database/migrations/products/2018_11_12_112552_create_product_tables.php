<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_cate', function (Blueprint $table) {
            $table->bigIncrements('cate_id')->unsigned();
            $table->string('cate_name', 50)->unique();
            $table->string('cate_desc', 500)->nullable();
            $table->string('attribute_id_list', 500)->nullable();
            $table->tinyInteger('state')->default(1);
            $table->timestamps();
        });

        Schema::create('product', function (Blueprint $table) {
            $table->bigIncrements('product_id')->unsigned();
            $table->bigInteger('product_cate_id')->unsigned();
            $table->foreign('product_cate_id')->references('cate_id')->on('product_cate');
            $table->string('product_desc', 500);
            $table->string('product_name', 50);
            $table->tinyInteger('state')->default(1);
            $table->timestamps();
        });

        Schema::create('product_attribute', function (Blueprint $table) {
            $table->bigIncrements('attribute_id')->unsigned();
            $table->string('attribute_name', 50)->unique();
            $table->string('attribute_tag', 50);
            $table->string('attribute_desc', 500);
            $table->tinyInteger('type')->default(1);
            $table->string('enum_values', 1000);
            $table->string('default_value', 500);
            $table->tinyInteger('state')->default(1);
            $table->timestamps();
        });

        Schema::create('product_attribute_value', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('product_id')->unsigned();
            $table->bigInteger('attribute_id')->unsigned();
            $table->foreign('product_id')->references('product_id')->on('product');
            $table->foreign('attribute_id')->references('attribute_id')->on('product_attribute');
            $table->string('value', 500);
            $table->timestamps();
            $table->unique('product_id', 'attribute_id');
        });

        Schema::create('product_image', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('product_id')->on('product');
            $table->string('url', 500);
            $table->string('title', 50);
            $table->string('desc', 500);
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
        Schema::dropIfExists('product_cate');
        Schema::dropIfExists('product');
        Schema::dropIfExists('product_attribute');
        Schema::dropIfExists('product_attribute_value');
        Schema::dropIfExists('product_image');
    }

}
