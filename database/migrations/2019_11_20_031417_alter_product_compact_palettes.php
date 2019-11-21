<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductCompactPalettes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_compactpalette', function (Blueprint $table) {
            $table->string('pan_well_width')->nullable()->change();
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
            $table->decimal('pan_well_width')->nullable()->change();
        });
    }
}
