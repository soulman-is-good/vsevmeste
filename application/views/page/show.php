<?php
$pages = Page::get(array('status'=>'1'));
?>
<div class="body" style="position: relative">
    <h1 style="font-size: 30px;margin:25px 0"><?=$model->title?></h1>
    <div class="right-bar item-show-bar" style="float:left">
        <div class="pane">
            <div class="pane-cont">
                <?foreach($pages as $page):?>
                <a href="/<?=$page->name?>.phtml"><?=X3_Html::encode($page->title)?></a><br/>
                <?endforeach;?>
            </div>
        </div>
    </div>
    <div class="pane" style="margin-bottom:40px;width:600px;float:right;">
        <div class="pane-cont">
            <?=$model->text?>
        </div>
    </div>
</div>
