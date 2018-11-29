<?php
namespace App\Admin\Widgets\Action;

class GalleryBtn
{
    const STYLE_GRID_IMAGES = 1;
    const STYLE_DETAIL_TOOL = 2;

    protected $id;
    protected $imagesCount;
    protected $tag;
    protected $style;

    public function __construct($imagesCount, $id, $tag, $style = self::STYLE_GRID_IMAGES)
    {
        $this->id = $id;
        $this->imagesCount = $imagesCount;
        $this->tag = $tag;
        $this->style = $style;
    }

    protected function render()
    {
        $content = '';
        if (self::STYLE_GRID_IMAGES == $this->style) {
            if ($this->imagesCount) {
                $content = "<a href='gallery/".$this->tag."/{$this->id}' class='btn btn-xs btn-success'><i class='fa fa-image'></i>&nbsp;&nbsp;{$this->imagesCount}</a>";
            } else {
                $content = "<button type='button' disabled='disabled' class='btn btn-xs btn-default'><i class='fa fa-image'></i>&nbsp;&nbsp;{$this->imagesCount}</button>";
            }
        } elseif (self::STYLE_DETAIL_TOOL == $this->style) {
            if ($this->imagesCount) {
                $content = '<a href="/'.config('admin.route.prefix').'/gallery/'.$this->tag.'/'.$this->id.'" class="btn btn-sm btn-success" style="margin-right: 5px;"><i class="fa fa-image"></i>&emsp;'.$this->imagesCount.'&nbsp;images</a>';
            } else {
                $content = '<button type="button" class="btn btn-sm btn-default" disabled="disabled" style="width: 100px;margin-right: 5px;"><i class="fa fa-image"></i>&emsp;No&nbsp;&nbsp;image</button>';
            }
        }
        return $content;
    }

    public function __toString()
    {
        return $this->render();
    }
}