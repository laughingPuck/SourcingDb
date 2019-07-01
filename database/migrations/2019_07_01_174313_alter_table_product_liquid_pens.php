<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableProductLiquidPens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_liquidpen', function (Blueprint $table) {
            $table->string('barrel_material')->nullable()->after('barrel_shape');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_liquidpen', function (Blueprint $table) {
            $table->dropColumn(['barrel_material']);
        });
    }
}
