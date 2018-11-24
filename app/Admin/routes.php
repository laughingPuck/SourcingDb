<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('/stick_with_cup', ProductStickwcupController::class);
    $router->resource('/vial', ProductVialController::class);
    $router->resource('/compact_palette', ProductCompactpaletteController::class);

    $router->get('/gallery/{cate}/{id}', 'ImageGalleryController@index');

//    $router->get('/product_attribute/{id}', 'ProductCategoryController@attributeEdit');
    $router->resource('/product_categories', ProductCategoryController::class);

});
