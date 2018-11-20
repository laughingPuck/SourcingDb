<?php

namespace App\Admin\Widgets;

use Encore\Admin\Widgets\Box;

class ToolBox extends Box
{
    public function addTool($tool)
    {
        $this->tools[] = $tool;
        return $this;
    }
}