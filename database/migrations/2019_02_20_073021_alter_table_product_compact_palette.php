<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableProductCompactPalette extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_compactpalette', function (Blueprint $table) {
            $table->string('edges_style')->nullable()->after('shape');
            $table->string('closure_mechanism')->nullable()->after('window');
            $table->string('mold_status')->nullable()->after('price');

            $table->dropColumn(['injector_pin', 'latch_system', 'storage_location', 'sample_available', 'related_projects']);
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
            $table->dropColumn(['edges_style', 'closure_mechanism', 'mold_status']);

            $table->tinyInteger('injector_pin')->unsigned()->nullable();
            $table->string('latch_system')->nullable();
            $table->string('storage_location')->nullable();
            $table->string('sample_available')->nullable();
            $table->string('related_projects')->nullable();
        });
    }
}
