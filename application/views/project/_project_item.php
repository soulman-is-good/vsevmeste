<?php
if (!isset($styles))
    $styles = "";
if(is_array($styles)){
    array_walk($styles,function(&$item, $key){return $item = $key . ":" . $item;});
    $styles = implode(';', $styles);
}
//$user = User::getByPk($model->user_id);    
?>
<div class="project_cont" style="<?=$styles?>">
    <div class="green_bg" style="position: relative">
            <?if(isset($lk) && $model->user_id==X3::user()->id):?>
                <?if($model->status==0):?>
                    <span style="position: absolute;right:0;top:0;padding: 2px;background: #990000;color:#FFF;font-size: 12px; font-weight: bold;">на модерации</span>
                <?elseif($model->status==1):?>
                    <span style="position: absolute;right:0;top:0;padding: 2px;background: #009900;color:#FFF;font-size: 12px; font-weight: bold;">прошел модерацию</span>
                <?endif;?>
            <?endif;?>
            <?if($model->partner()!==null && $model->partner()->status == 1):?>
            <span class="partner-mark"><i>&nbsp;</i><b>&nbsp;</b>
                <a title="<?=$model->partner()->user_id()->fullName?>" href="/user/<?=$model->partner()->user_id?>/">
                P
                </a>
            </span>
            <?endif;?>
        <div class="white_bg">
            <div class="project_pic" style="background-image: url(/uploads/Project/208x160/<?=$model->image?>)"><a href="/<?=$model->name?>-project/"><img src="/images/_zero.gif" alt="" /></a></div>
            <div class="project_text_cont">
                <div class="project_title" style="margin-bottom: 10px;"><a href="/<?=$model->name?>-project.html" class="green_link t16"><b><?=X3_Html::encode($model->title)?></b></a></div>
                <div class="name"><a href="/user/<?=$model->user_id()->id?>/projects.html" class="grey_link"><?=$model->user_id()->fullName?></a></div>
                <div class="city"><img src="/images/location.png" alt="" /><a href="/project/city/<?=$model->city_id()->id?>.html" class="grey_link"><?=X3_Html::encode($model->city_id()->title)?></a></div>
                <div class="project_text"><p><?=  X3_String::create(str_replace("&nbsp;"," ",X3_Html::encode($model->short_content)))->carefullCut(128)?></p></div>
                <div style="float: left;"><b><?=  number_format($model->needed_sum,0,' ',' ')?></b> тенге</div>
                <div style="float: right;"><b><?=$model->percentReal?></b> %</div>
                <div class="clear"></div>
                <div class="finish_cont">
                    <div class="finish" style="width: <?=$model->percentDone?>%;"></div>
                </div>
                <i><?=$model->timeLeft?></i><br />
                <i><?=$model->investmentsCount?></i>
            </div>
        </div>
    </div>
    <div class="project_shadow"></div>
</div>
