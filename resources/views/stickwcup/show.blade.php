@extends('layouts.default2')
@section('content')
<?php
use phpGrid\C_DataGrid;

require_once(public_path() ."/phpGrid_Enterprise_v7.2.7/conf.php");

$dg = new C_DataGrid("SELECT * FROM stickwcup");


//$dg->set_col_datetime("created_at");
//$dg->set_col_date("updated_at");

$dg->enable_advanced_search(true);
$dg->enable_edit('FORM', 'CRUD');
$dg->enable_autowidth(true);
$dg->set_scroll(true);

$dg->set_col_title("id", "ID");
$dg->set_col_title("cosmopak_item", "Cosmopak Item#");
$dg->set_col_title("vendor_item", "Vendor Item#");
$dg->set_col_title("manufactory_name", "Manufactory Name");
$dg->set_col_title("item_description", "Item Description");
$dg->set_col_title("material", "material");
$dg->set_col_title("shape", "Shape");
$dg->set_col_title("style", "Style");
$dg->set_col_title("cup", "Cup #");
$dg->set_col_title("cup_size", "Cup Size/ 1 Cup");
$dg->set_col_title("cover_material", "Cover Material");
$dg->set_col_title("overall_length", "Overall Lenght");
$dg->set_col_title("overall_width", "Overall Diameter / Width");
$dg->set_col_title("overall_height", "Overall height");
$dg->set_col_title("mechanism", "Mechanism");
$dg->set_col_title("storage_location", "Storage Location");
$dg->set_col_title("sample_available", "Sample Available & Number");
$dg->set_col_title("related_projects", "Related Projects");
$dg->set_col_title("moq", "MOQ");
$dg->set_col_title("price", "Price of MOQ no deco");
$dg->set_col_title("mold_status", "Mold Status");
$dg->set_col_title("created_at", "Created At");
$dg->set_col_title("updated_at", "Updated At");

$dg->set_col_property("id", array("formoptions"=>array("rowpos"=>1,"colpos"=>1)));
$dg->set_col_property("cosmopak_item", array("formoptions"=>array("rowpos"=>1,"colpos"=>2)));
$dg->set_col_property("vendor_item", array("formoptions"=>array("rowpos"=>2,"colpos"=>1)));
$dg->set_col_property("manufactory_name", array("formoptions"=>array("rowpos"=>2,"colpos"=>2)));
$dg->set_col_property("item_description", array("formoptions"=>array("rowpos"=>3,"colpos"=>1)));
$dg->set_col_property("material", array("formoptions"=>array("rowpos"=>3,"colpos"=>2)));
$dg->set_col_property("shape", array("formoptions"=>array("rowpos"=>4,"colpos"=>2)));
$dg->set_col_property("style", array("formoptions"=>array("rowpos"=>5,"colpos"=>1)));
$dg->set_col_property("cup", array("formoptions"=>array("rowpos"=>5,"colpos"=>2)));
$dg->set_col_property("cup_size", array("formoptions"=>array("rowpos"=>6,"colpos"=>1)));
$dg->set_col_property("cover_material", array("formoptions"=>array("rowpos"=>6,"colpos"=>2)));
$dg->set_col_property("overall_length", array("formoptions"=>array("rowpos"=>7,"colpos"=>1)));
$dg->set_col_property("overall_width", array("formoptions"=>array("rowpos"=>7,"colpos"=>2)));
$dg->set_col_property("overall_height", array("formoptions"=>array("rowpos"=>8,"colpos"=>1)));
$dg->set_col_property("mechanism", array("formoptions"=>array("rowpos"=>8,"colpos"=>2)));
$dg->set_col_property("storage_location", array("formoptions"=>array("rowpos"=>9,"colpos"=>1)));
$dg->set_col_property("sample_available", array("formoptions"=>array("rowpos"=>9,"colpos"=>2)));
$dg->set_col_property("related_projects", array("formoptions"=>array("rowpos"=>10,"colpos"=>1)));
$dg->set_col_property("moq", array("formoptions"=>array("rowpos"=>10,"colpos"=>2)));
$dg->set_col_property("price", array("formoptions"=>array("rowpos"=>11,"colpos"=>1)));
$dg->set_col_property("mold_status", array("formoptions"=>array("rowpos"=>11,"colpos"=>2)));
$dg->set_col_property("created_at", array("formoptions"=>array("rowpos"=>12,"colpos"=>1)));
$dg->set_col_property("updated_at", array("formoptions"=>array("rowpos"=>12,"colpos"=>2)));
$dg->set_form_dimension(1000,590);
//$dg->obj_md

//$dg -> set_col_hidden("password");
//$dg -> set_col_required("name, email, password, role_id");
//$dg -> set_col_readonly("id, created_at, updated_at");
//$dg -> set_col_default("created_at", date("Y-m-d H:i:s"));
//$dg -> set_col_default("updated_at", date("Y-m-d H:i:s"));

$sdg = new C_DataGrid("SELECT * FROM stickwcup_image");
$sdg->set_col_fileupload("description", "/upload");
$sdg->enable_edit();


$dg -> set_subgrid($sdg, 'id');
//$dg -> set_masterdetail($sdg, 'id');
$dg -> display();
?>
@stop