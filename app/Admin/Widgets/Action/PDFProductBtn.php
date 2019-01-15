<?php
namespace App\Admin\Widgets\Action;

/**
 * need view('admin.tool.product_mail', $cate);
 * Class MailProductBtn
 * @package App\Admin\Widgets\Action
 */
class PDFProductBtn
{
    const STYLE_GRID_ACTION = 1;
    const STYLE_DETAIL_TOOL = 2;

    protected $id;
    protected $cate;
    protected $style;

    public function __construct($id, $cate, $style = self::STYLE_GRID_ACTION)
    {
        $this->style = $style;
        $this->id = $id;
        $this->cate = $cate;
    }

    public function render()
    {
        $content = '';
        if (self::STYLE_GRID_ACTION == $this->style) {
            $content = '<a href="product_pdf/download/'.$this->cate.'/'.$this->id.'" target="_blank" class="btn btn-xs btn-info"><i class="fa fa-file-pdf-o"></i>&nbsp;&nbsp;PDF</a>';
        } elseif (self::STYLE_DETAIL_TOOL == $this->style) {
            $content = '<a href="product_pdf/download/'.$this->cate.'/'.$this->id.'" style="margin-right: 5px;"><i class="fa fa-file-pdf-o"></i>&nbsp;&nbsp;PDF</a>';
        }
        return $content;
    }

    public function __toString()
    {
        return $this->render();
    }
}