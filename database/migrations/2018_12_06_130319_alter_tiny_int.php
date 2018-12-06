<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTinyInt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_compactpalette', function (Blueprint $table) {
            $table->string('mirror')->nullable()->change();
            $table->string('window')->nullable()->change();
            $table->string('applicator_well')->nullable()->change();
            $table->string('injector_pin')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_compactpalette', function (Blueprint $table) {
            $table->tinyInteger('mirror')->unsigned()->nullable()->change();
            $table->tinyInteger('window')->unsigned()->nullable()->change();
            $table->tinyInteger('applicator_well')->unsigned()->nullable()->change();
            $table->tinyInteger('injector_pin')->unsigned()->nullable()->change();
        });
    }
}
