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
        Schema::create('product_attribute', function (Blueprint $table) {
            $table->bigIncrements('attribute_id')->unsigned();
            $table->string('attribute_name', 50)->unique();
            $table->string('attribute_tag', 50)->nullable();
            $table->string('attribute_desc', 500)->nullable();
            $table->tinyInteger('type')->default(1);
            $table->string('enum_values', 1000)->nullable();
            $table->string('default_value', 500)->nullable();
            $table->tinyInteger('state')->default(1);
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
        Schema::dropIfExists('product_attribute');
    }
}
