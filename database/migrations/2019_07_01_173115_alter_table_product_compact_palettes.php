<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableProductCompactPalettes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_compactpalette', function (Blueprint $table) {
            $table->decimal('pan_well_depth')->nullable()->after('pan_well_height');
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
            $table->dropColumn(['pan_well_depth']);
        });
    }
}
