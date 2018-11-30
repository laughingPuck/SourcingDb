<?php

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */

use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid\Column;

Encore\Admin\Form::forget(['map', 'editor']);
Admin::js('/js/lightgallery-all.min.js');

Column::extend('width', function ($value, $width){
    return "<p style='white-space: nowrap;overflow: hidden;text-overflow: ellipsis;min-width: {$width}px;'>$value</p>";
});
