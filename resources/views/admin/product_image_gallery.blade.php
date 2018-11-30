<link href="/css/lightgallery.css" rel="stylesheet">
<div class="container-fluid">
    <div id="gallery" class="row">

        <?php foreach ($imageList as $v): ?>
            <div style="display: inline-block;background-color: #333;" class="col-sm-6 col-md-4 thumbnail" data-src="/<?=$v->url;?>" data-sub-html="<h4><?=$v->title;?></h4><p><?=$v->desc;?></p>">
                <a href="">
                    <img class="img-responsive" style="max-height: 250px;" src="/<?=$v->url;?>">
                </a>
            </div>
        <?php endforeach; ?>

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#gallery').lightGallery();
    });
</script>



