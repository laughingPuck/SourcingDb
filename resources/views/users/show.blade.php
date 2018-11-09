@extends('layouts.default2')
@section('content')
<?php
use phpGrid\C_DataGrid;

require_once(public_path() ."/phpGrid_Enterprise_v7.2.7/conf.php");

$dg = new C_DataGrid("SELECT id,name,email,password,role_id,created_at,updated_at FROM users");

$dg->set_col_title("id", "ID");
$dg->set_col_title("name", "User Name");
$dg->set_col_title("email", "EMail");
$dg->set_col_title("role_id", "Role");
$dg->set_col_title("created_at", "Created At");
$dg->set_col_title("updated_at", "Updated At");

$dg->set_col_edittype("role_id", "select", "Select role_id,role_name from roles",false);
$dg->set_col_edittype("password", "password");

$dg->enable_autowidth(true);
$dg->set_scroll(true);

//$dg->set_col_datetime("created_at");
//$dg->set_col_date("updated_at");

$dg->enable_advanced_search(true);
$dg->enable_edit('FORM', 'CRUD');

//$dg->add_event("jqGridAddEditBeforeSubmit", $beforeSubmit);
//$dg->set_jq_editurl("/users/edit");

$dg -> set_col_hidden("password");
$dg -> set_col_required("name, email, password, role_id");
$dg -> set_col_readonly("id, created_at, updated_at");
$dg -> set_col_default("created_at", date("Y-m-d H:i:s"));
$dg -> set_col_default("updated_at", date("Y-m-d H:i:s"));
$dg -> display();
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/simplemodal/1.4.4/jquery.simplemodal.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
    jQuery("#edit_users").off("click");
    jQuery("#edit_users").on("click", function() {
        var selrow = jQuery("#users").jqGrid("getGridParam","selrow");
        if(selrow != null)
            document.location.href = '/users/'+selrow+'/edit';
    });
});
</script>
<!--<div id="myCarousel" class="carousel slide">
    <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0"
            class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
        <li data-target="#myCarousel" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
        <div class="item active">
            <img src="/wp-content/uploads/2014/07/slide1.png" alt="First slide">
        </div>
        <div class="item">
            <img src="/wp-content/uploads/2014/07/slide2.png" alt="Second slide">
        </div>
        <div class="item">
            <img src="/wp-content/uploads/2014/07/slide3.png" alt="Third slide">
        </div>
    </div>
        <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    <div style="text-align:center;">
        <input type="button" class="btn start-slide" value="Start">
        <input type="button" class="btn pause-slide" value="Pause">
        <input type="button" class="btn prev-slide" value="Previous Slide">
        <input type="button" class="btn next-slide" value="Next Slide">
        <input type="button" class="btn slide-one" value="Slide 1">
        <input type="button" class="btn slide-two" value="Slide 2">
        <input type="button" class="btn slide-three" value="Slide 3">
    </div>
</div> -->
@stop