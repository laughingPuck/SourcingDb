@extends('layouts.default')
@section('content')
<?php
require_once(public_path() ."/phpGrid_Lite/conf.php");

$dg = new c_DataGrid("SELECT id,name,email,created_at,updated_at FROM users");
$dg->enable_advanced_search(true);
//$dg->enable_export('EXCEL');
$dg->enable_edit('FORM', 'CRU');
$dg->set_col_title("id", "ID");
$dg->set_col_title("name", "User Name");
$dg->set_col_title("email", "EMail");
$dg->set_col_title("created_at", "Created At");
$dg->set_col_title("updated_at", "Updated At");
$dg -> display();
//php artisan tinker
//App\Models\User::create(['name'=> 'Admin', 'role_id'=>'1', 'email'=>'gyeon_woostar@163.com', 'password'=>bcrypt('password')])

?>


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