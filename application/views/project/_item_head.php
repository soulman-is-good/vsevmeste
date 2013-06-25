<div class="item-head">
    <div class="item-head-body">
        <img class="logo" src="/images/03.jpg" alt="" />
        <div class="item-desc">
            <h1><?=$model->title?></h1>
            <p><?=$model->short_content?></p>
                <?if($model->donate):?>
                <div class="donate-action"><?=X3::translate('Благотворительная акция')?></div>
                <?endif;?>
            <div class="name"><a href="/user/<?=$model->user_id()->id?>/projects.html" class="grey_link"><?=$model->user_id()->fullName?></a></div>
            <div class="city"><img src="/images/location.png" alt="" /><a href="/project/city/<?=$model->city_id()->id?>.html" class="grey_link"><?=$model->city_id()->title?></a></div>
        </div>
        <div class="clear">&nbsp;</div>
    </div>
</div>