<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableProductVial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_vial', function (Blueprint $table) {
            $table->string('edges_style')->nullable()->after('shape');
            $table->decimal('ofc')->nullable()->after('vial');
            $table->decimal('estimate_capacity')->nullable()->after('ofc');
            $table->string('color')->nullable()->after('estimate_capacity');
            $table->string('inner_cap_material')->nullable()->after('cap_material');
            $table->string('collar_material')->nullable()->after('base_material');
            $table->string('rod_material')->nullable()->after('collar_material');
            $table->string('available_applicator_options')->nullable()->after('rod_material');
            $table->string('wall_style')->nullable()->after('available_applicator_options');
            $table->string('closure_mechanism')->nullable()->after('wall_style');

            $table->dropColumn(['stem_material', 'collar', 'overall_length', 'storage_location', 'sample_available', 'related_projects', 'ofc_vial']);
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
            $table->dropColumn(['edges_style', 'ofc', 'estimate_capacity', 'color', 'inner_cap_material', 'collar_material', 'rod_material', 'available_applicator_options', 'wall_style', 'closure_mechanism']);

            $table->string('stem_material')->nullable();
            $table->decimal('overall_length')->nullable();
            $table->string('collar')->nullable();
            $table->string('storage_location')->nullable();
            $table->string('sample_available')->nullable();
            $table->string('related_projects')->nullable();

            $table->decimal('ofc_vial')->nullable();
        });
    }
}
