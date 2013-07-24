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
    <div class="green_bg">
        <div class="white_bg">
            <div class="project_pic"><a href="/<?=$model->name?>-project/"><img src="/images/03.jpg" alt="" /></a></div>
            <div class="project_text_cont">
                <div class="project_title" style="margin-bottom: 10px;"><a href="/<?=$model->name?>-project.html" class="green_link t16"><b><?=$model->title?></b></a></div>
                <div class="name"><a href="/user/<?=$model->user_id()->id?>/projects.html" class="grey_link"><?=$model->user_id()->fullName?></a></div>
                <div class="city"><img src="/images/location.png" alt="" /><a href="/project/city/<?=$model->city_id()->id?>.html" class="grey_link"><?=$model->city_id()->title?></a></div>
                <div class="project_text"><p><?=$model->short_content?></p></div>
                <div style="float: left;"><b><?=  number_format($model->needed_sum,0,' ',' ')?></b> тенге</div>
                <div style="float: right;"><b><?=$model->percentDone?></b> %</div>
                <div class="clear"></div>
                <div class="finish_cont">
                    <div class="finish" style="width: <?=$model->percentDone?>%;"></div>
                </div>
                <i><?=$model->timeLeft?></i><br />
                <i><b>0</b> вложений</i>
            </div>
        </div>
    </div>
    <div class="project_shadow"></div>
</div>