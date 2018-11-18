<?php foreach ($imageList as $v): ?>
<a data-img-src="/<?=$v->url;?>" data-img-title="<?=$v->title;?>" data-img-desc="<?=$v->desc;?>" class="image-pointer" style="cursor: pointer;display: inline-block;color: #666;font-weight: bold;">
<div class="imgBox img-thumbnail" style="width: 250px;margin-right: 10px;float: left;">
    <div class="imgSrc" style="overflow-y: scroll;height: 150px;">
        <img src="/<?=$v->url;?>" class="img-responsive center-block" style="max-height: 150px;" alt="<?=$v->title;?>">
    </div>
    <hr/>
    <p style="text-align: center;margin-top:5px;"><?=$v->title;?></p>
</div>
</a>

<?php endforeach; ?>

<div class="modal fade bs-example-modal-lg" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel">
    <div class="modal-dialog modal-lg" style="width:710px;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="imageModalLabel"></h4>
            </div>
            <div class="modal-body">
                <div style="overflow-y: scroll;height: 410px;">
                    <img id="imageModalSrc" src="" class="img-responsive center-block img-thumbnail" />
                </div>
                <div class="bs-callout bs-callout-info" id="callout-type-b-i-elems">
                    <h4>Image Description:</h4>
                    <p id="imageModalDesc"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $('.image-pointer').click(function(){
        $('#imageModalLabel').html($(this).attr('data-img-title'));
        $('#imageModalDesc').html($(this).attr('data-img-desc'));
        $('#imageModalSrc').attr('src', $(this).attr('data-img-src'));
        $('#imageModal').modal('show');
    });
</script>
