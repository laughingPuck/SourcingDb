<?php
namespace App\Admin\Widgets\Action;

/**
 * need view('admin.tool.product_mail', $cate);
 * Class MailProductBtn
 * @package App\Admin\Widgets\Action
 */
class MailProductBtn
{
    const STYLE_GRID_ACTION = 1;
    const STYLE_DETAIL_TOOL = 2;

    protected $id;
    protected $style;

    public function __construct($id, $style = self::STYLE_GRID_ACTION)
    {
        $this->style = $style;
        $this->id = $id;
    }

    public function render()
    {
        $script = "javascript:productGridMailBox('{$this->id}');";
        $content = '';
        if (self::STYLE_GRID_ACTION == $this->style) {
            $content = '<a href="'.$script.'" class="btn btn-xs btn-success"><i class="fa fa-envelope"></i>&nbsp;&nbsp;Mail</a>';
        } elseif (self::STYLE_DETAIL_TOOL == $this->style) {
            $content = '<a href="'.$script.'" class="btn btn-sm btn-info" style="margin-right: 5px;"><i class="fa fa-envelope"></i>&nbsp;&nbsp;Email to</a>';
        }
        return $content;
    }

    public function __toString()
    {
        return $this->render();
    }
}