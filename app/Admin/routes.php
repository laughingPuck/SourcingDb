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
    $router->resource('/vials', ProductVialController::class);
    $router->resource('/compacts_palettes', ProductCompactpaletteController::class);
    $router->resource('/bottles', ProductBottleController::class);
    $router->resource('/jars_pots', ProductJarpotController::class);
    $router->resource('/solid_formula_sticks', ProductStickController::class);
    $router->resource('/brushes', ProductBrushController::class);
    $router->resource('/liquid_formula_pens', ProductLiquidpenController::class);
    $router->resource('/pencils', ProductPencilController::class);
    $router->resource('/tubes', ProductTubeController::class);
    $router->resource('/others', ProductOtherController::class);
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
