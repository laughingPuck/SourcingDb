<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductCatesTable extends Migration
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
        Schema::dropIfExists('product_cate');
    }
}
