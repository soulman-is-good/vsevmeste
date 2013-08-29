<div class="item-head">
    <div class="item-head-body"  style="position: relative">
        <?if($model->partner()!==null && $model->partner()->status == 1):?>
        <span class="partner-mark" style="left:31px;top:120px"><i>&nbsp;</i><b>&nbsp;</b>
            <a href="/user/<?=$model->partner()->user_id?>/">
            <?=$model->partner()->user_id()->fullName?>
            </a>
        </span>
        <?endif;?>
        <img class="logo" src="/uploads/Project/220x220xw/<?=$model->image?>" alt="" />
        <div class="item-desc">
            <h1><?=X3_Html::encode($model->title)?> <?if($model->status==0 && $model->user_id==X3::user()->id):?><span style="padding: 2px;background: #990000;color:#FFF;font-size: 12px; font-weight: bold;">на модерации</span><?endif;?></h1>
            <p><?=X3_Html::encode($model->short_content)?></p>
                <?if($model->donate):?>
                <div class="donate-action"><?=X3::translate('Благотворительная акция')?></div>
                <?endif;?>
        <?if(X3::user()->id == $model->user_id && $model->status == 0):?>
            <a href="/project/edit/id/<?=$model->id?>.html" style="font-size: 16px;font-weight: bold;border-bottom: 1px solid #ADD299;display: inline-block">Редактировать проект</a>
        <?endif;?>
            <div class="name"><a href="/user/<?=$model->user_id()->id?>/projects.html" class="grey_link"><?=$model->user_id()->fullName?></a></div>
            <div class="city"><img src="/images/location.png" alt="" /><a href="/project/city/<?=$model->city_id()->id?>.html" class="grey_link"><?=X3_Html::encode($model->city_id()->title)?></a></div>
    <?if(X3::user()->partner):?>
        <?if($model->partner()===null):?>
            <div class="part"><a href="/project/partner/<?=$model->id?>.html" class="green_link">Стать партнером этого проекта</a></div>
        <?elseif($model->partner()->status == 0 && $model->partner()->user_id == X3::user()->id):?>
            <div class="part"><i style="color:blue">Вы отправили заявку на почту владельца проекта</i></div>
        <?elseif($model->partner()->status == 0):?>
            <div class="part"><a href="/project/partner/<?=$model->id?>.html" class="green_link">Стать партнером этого проекта</a></div>
        <?elseif($model->partner()->status == 1 && $model->partner()->user_id == X3::user()->id):?>
            <div class="part">Вы являетесь партнером этого проекта</div>
        <?endif;?>
    <?endif;?>
        </div>
        <div class="clear">&nbsp;</div>
    </div>
</div>