<link rel="stylesheet" href="/css/effects.css">

<div class="container-fluid">
    <div class="row">

        <?php foreach($imageList as $v): ?>

        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="hover ehover4">
                <img class="img-responsive" src="/<?=$v->cover_image?>" alt="<?=$v->cate_name?>">
                <a href="<?=$urlPrefix?><?=$v->link?>">
                    <div class="overlay">
                        <h2><?=$v->cate_name?></h2>
                        <button class="info" >Click to show</button>
                    </div>
                </a>
            </div>
        </div>

        <?php endforeach; ?>

    </div>
</div>