<?php
namespace App\Admin\Widgets\Action;

class EditProductBtn
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
        return '<a href="'.$this->tag.'/'.$this->id.'/edit" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i>&nbsp;&nbsp;Edit</a>';
    }

    public function __toString()
    {
        return $this->render();
    }
}