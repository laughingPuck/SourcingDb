<?php
namespace App\Admin\Widgets\Action;

class DocumentBtn
{
    const STYLE_GRID_FILES = 1;
    const STYLE_DETAIL_TOOL = 2;

    protected $id;
    protected $filesCount;
    protected $tag;
    protected $style;

    public function __construct($filesCount, $id, $tag, $style = self::STYLE_GRID_FILES)
    {
        $this->id = $id;
        $this->filesCount = $filesCount;
        $this->tag = $tag;
        $this->style = $style;
    }

    public function render()
    {
        $content = '';
        if (self::STYLE_GRID_FILES == $this->style) {
            if ($this->filesCount) {
                $content = "<a href='document/".$this->tag."/{$this->id}' class='btn btn-xs btn-info'><i class='fa fa-file-text-o'></i>&nbsp;&nbsp;{$this->filesCount}</a>";
            } else {
                $content = "<button type='button' disabled='disabled' class='btn btn-xs btn-default'><i class='fa fa-file-text-o'></i>&nbsp;&nbsp;{$this->filesCount}</button>";
            }
        } elseif (self::STYLE_DETAIL_TOOL == $this->style) {
            $btnTxt = 'file';
            if ($this->filesCount > 1) {
                $btnTxt = 'files';
            }
            if ($this->filesCount) {
                $content = '<a href="/'.config('admin.route.prefix').'/document/'.$this->tag.'/'.$this->id.'" class="btn btn-sm btn-info" style="margin-right: 5px;"><i class="fa fa-file-text-o"></i>&emsp;'.$this->filesCount.'&nbsp;'.$btnTxt.'</a>';
            } else {
                $content = '<button type="button" class="btn btn-sm btn-default" disabled="disabled" style="width: 100px;margin-right: 5px;"><i class="fa fa-file-text-o"></i>&emsp;No&nbsp;&nbsp;files</button>';
            }
        }
        return $content;
    }

    public function __toString()
    {
        return $this->render();
    }
}