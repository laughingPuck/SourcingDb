<?php
namespace App\Admin\Widgets\Action;

class ViewRowAction
{
    protected $id;
    protected $tag;

    public function __construct($id, $tag)
    {
        $this->id = $id;
        $this->tag = $tag;
    }

    public function render()
    {
        return '<a href="'.$this->tag.'/'.$this->id.'" class="btn btn-xs btn-info"><i class="fa fa-eye"></i>&nbsp;&nbsp;View</a>';
    }

    public function __toString()
    {
        return $this->render();
    }
}