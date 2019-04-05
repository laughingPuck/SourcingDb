<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {
    /** home page **/
    $router->get('/', 'HomeController@index');
    /** products **/
    $router->resource('/stick_with_cup', ProductStickwcupController::class);
    $router->resource('/vial', ProductVialController::class);
    $router->resource('/compact_palette', ProductCompactpaletteController::class);
    $router->resource('/bottle', ProductBottleController::class);
    $router->resource('/jar_pot', ProductJarpotController::class);
    $router->resource('/stick', ProductStickController::class);
    $router->resource('/brush', ProductBrushController::class);
    $router->resource('/liquid_pen', ProductLiquidpenController::class);
    $router->resource('/pencil', ProductPencilController::class);
    $router->resource('/tube', ProductTubeController::class);
    $router->resource('/other', ProductOtherController::class);
//    $router->resource('/import', ImportDataController::class);
    /** product categories **/
    $router->resource('/product_categories', ProductCategoryController::class);
    /** product email log **/
    $router->resource('/product_email_logs', ProductEmailLogController::class);

    /** others **/
    $router->get('/gallery/{cate}/{id}', 'ImageGalleryController@index');
    $router->get('/document/{cate}/{id}', 'DocumentsController@index');

//    $router->get('/product_attribute/{id}', 'ProductCategoryController@attributeEdit');

    /** tools **/
    $router->post('/send_product_mail/product_grid', 'SendMailController@productGridMail');
    $router->get('/import', 'ImportDataController@index');
    $router->post('/import', 'ImportDataController@import');

    $router->get('/product_pdf/download/{cate}/{id}', 'ProductPDFController@download');
    $router->get('/product_pdf/document/{cate}/{id}', 'ProductPDFController@document');

});
