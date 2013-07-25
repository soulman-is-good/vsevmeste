<?php
$form = new Form($model);
?>
<div class="item-head">
    <div class="item-head-body">
        <h1 style="font-size:30px;margin:0 0 0 30px">Размещение проекта</h1>
    </div>
</div>
<div class="body project-add" style="position: relative">
    <div class="tabs" style="top:-79px">
        <ul>
            <li class="active"><em>1</em> - Проект</li>
            <li><a href="#" onclick="return false;"><em>2</em> - Необходимая сумма</a></li>
            <?if(!$user->filled):?>
            <li><a href="#" onclick="return false;"><em>3</em> - Личная информация</a></li>
            <?endif;?>
        </ul>
    </div>
    <div class="pane" style="margin:30px 0;width:615px">
        <div class="pane-cont">
            <div class="form">
                <?if($model->getTable()->getErrors()):?>
                <?=  X3_Html::errorSummary($model);?>
               <?endif;?> 
                <?=$form->start();?>
                <h3>Опишите Ваш проект</h3>
                <div class="field">
                    <?=$form->select('city_id')?>
                    <div class="info">Выберите город проекта</div>
                </div>
                <div class="field">
                    <?=$form->input('title',array('placeholder'=>'Название проекта'))?>
                </div>
                <div class="field">
                    <?=$form->textarea('short_content',array('placeholder'=>'Краткое описание'))?>
                </div>
                <div class="field">
                    <?=$form->textarea('full_content')?>
                    <div class="info">Введите полное описание проекта</div>
                </div>
                <div class="field">
                    <div><strong>Изображение проекта 600x400</strong> <button id="upl" type="button">Загрузить</button> <i id="str"></i> </div>
                    <div id="blah"></div>
                    <?=$form->file('image',array('style'=>'position:absolute;left:-9999px;','id'=>'p-file'))?>
                </div>
                <div class="field">
                    <?=$form->input('video',array('placeholder'=>'Видео проекта (вставте ссылку с YouTube)'))?>
                </div>
                <div class="field">
                    <?=$form->select('category_id')?>
                </div>
                <?/*
                <div class="field">
                    <div><strong>Ссылки на проект на других источниках</strong></div>
                    <div><?=$form->input('links[]',array('placeholder'=>'Vkontakte'))?></div>
                    <div><?=$form->input('links[]',array('placeholder'=>'Facebook'))?></div>
                    <div><?=$form->input('links[]',array('placeholder'=>'Twitter'))?></div>
                    <div><?=$form->input('links[]',array('placeholder'=>'Мой Мир'))?></div>
                    <div><?=$form->input('links[]',array('placeholder'=>'YouTube'))?></div>
                </div>
                 */?>
                <div class="field">
                    <button type="submit">Далее</button>
                </div>
                <?=$form->end();?>
            </div>
        </div>
    </div>
</div>