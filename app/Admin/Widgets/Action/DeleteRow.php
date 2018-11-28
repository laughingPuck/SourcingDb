<?php
namespace App\Admin\Widgets\Action;

use Encore\Admin\Admin;

class DeleteRow
{
    protected $id;
    protected $tag;

    public function __construct($id, $tag)
    {
        $this->id = $id;
        $this->tag = $tag;
    }

    protected function script()
    {
        return <<<SCRIPT

$('.grid-row-delete').unbind('click').click(function() {

    var id = $(this).data('id');
    var tag = $(this).data('tag');

    swal({
        title: "Are you sure to delete this item ?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Confirm",
        showLoaderOnConfirm: true,
        cancelButtonText: "Cancel",
        preConfirm: function() {
        return new Promise(function(resolve) {
            $.ajax({
                    method: 'post',
                    url: '/'+ tag +'/' + id,
                    data: {
                _method:'delete',
                        _token:LA.token,
                    },
                    success: function (data) {
                $.pjax.reload('#pjax-container');

                resolve(data);
            }
                });
            });
    }
    }).then(function(result) {
        var data = result.value;
        if (typeof data === 'object') {
            if (data.status) {
                swal(data.message, '', 'success');
            } else {
                swal(data.message, '', 'error');
            }
        }
    });
});

SCRIPT;
    }

    protected function render()
    {
        Admin::script($this->script());

        return '<a href="javascript:void(0);" data-id="'.$this->id.'" data-tag="'.$this->tag.'" class="grid-row-delete btn btn-xs btn-danger" style="margin: 5px 5px;"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</a>';
    }

    public function __toString()
    {
        return $this->render();
    }
}