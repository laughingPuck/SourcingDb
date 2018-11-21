<?php

namespace App\Admin\Widgets;

use Encore\Admin\Layout\Content;

class AdminContent extends Content
{
    /**
     * Render this content.
     *
     * @return string
     */
    public function render()
    {
        $items = [
            'header'      => $this->header,
            'description' => $this->description,
            'breadcrumb'  => $this->breadcrumb,
            'content'     => $this->build(),
        ];

        return view('admin.content', $items)->render();
    }
}
