<link rel="stylesheet" href="/css/baguetteBox.min.css">
<link rel="stylesheet" href="/css/thumbnail-gallery.css">
<style>
    .intro{
        height: 45px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }
</style>
    <div class="container-fluid gallery-container" style="background-color: #f6f6f6;">
        <div class="tz-gallery">
            <div class="row">

                <?php foreach ($imageList as $v): ?>
                <div class="col-sm-6 col-md-4">
                    <div class="thumbnail">
                        <a class="lightbox" href="/<?=$v->url;?>">
                            <img src="/<?=$v->url;?>" alt="<?=$v->title;?>" style="max-height: 200px;">
                        </a>
                        <div class="caption">
                            <h3><?=$v->title;?></h3>
                            <p class="intro"><?=$v->desc;?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>

        </div>
    </div>
<script type="text/javascript" src="/js/baguetteBox.min.js"></script>
<script type="text/javascript">
    baguetteBox.run('.tz-gallery');
</script>
