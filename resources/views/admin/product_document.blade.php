<div class="container-fluid">
    <div id="gallery" class="row">

        <?php foreach ($fileList as $v): ?>
            <div class="col-sm-6 col-md-3 thumbnail" style="text-align:center;padding: 20px 0;margin: 5px;">
                <img src="/resource/pdf.png" width="100" alt="<?=$v->desc;?>">
                <br/>
                <p style="color: #777;"><?=$v->title;?></p>
                <div>
                    <a href="/<?=$adminPrefix;?>/product_pdf/document/<?=$cate;?>/<?=$v->id;?>" target="_blank"><i class="fa fa-download"></i>&nbsp;download</a>&emsp;
                    <a target="_blank" href="/<?=$v->url;?>"><i class="fa fa-eye"></i>&nbsp;preview</a>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
</div>





