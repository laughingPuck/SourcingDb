<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStickwcupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('image_stickwcup', function (Blueprint $table) {
            $table->tinyInteger('state')->default(1)->unsigned()->after('desc');
        });
        Schema::table('product_stickwcup', function (Blueprint $table) {
            $table->dropColumn('state');
        });
        Schema::table('product_stickwcup', function (Blueprint $table) {
            $table->tinyInteger('state')->default(1)->unsigned()->after('mold_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('image_stickwcup', function (Blueprint $table) {
            $table->dropColumn('state');
        });
        Schema::table('product_stickwcup', function (Blueprint $table) {
            $table->dropColumn('state');
        });
        Schema::table('product_stickwcup', function (Blueprint $table) {
            $table->tinyInteger('state')->unsigned()->after('mold_status');
        });
    }
}
