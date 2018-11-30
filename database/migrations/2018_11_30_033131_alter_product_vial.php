<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductVial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_vial', function (Blueprint $table) {
            $table->decimal('ofc_vial')->nullable()->after('vial');
            $table->string('applicator')->nullable()->after('ofc_vial');
            $table->tinyInteger('thick_wall')->unsigned()->nullable()->after('applicator');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_vial', function (Blueprint $table) {
            $table->dropColumn(['ofc_vial', 'applicator', 'thick_wall']);
        });
    }
}
