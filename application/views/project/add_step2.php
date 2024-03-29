<?php
$form = new Form($model);
$links = explode("\n",$model->links);
$links = array_merge($links, array_fill(0, 6, ''));
$Ts = X3::db()->query("SELECT tag FROM tags WHERE status='1'");
$ttags = array();
while (null!=($t = mysql_fetch_assoc($Ts)) && $ttags[] = array_shift($t)){}
?>
<div class="item-head">
    <div class="item-head-body">
        <h1 style="font-size:30px;margin:0 0 0 30px">Размещение проекта</h1>
    </div>
</div>
<div class="body project-add" style="position: relative">
    <?=X3_Widget::run('@views:_widgets:rules_mini.php', array(), array('cache'=>!X3_DEBUG))?>
    <div class="tabs" style="top:-79px">
        <ul>
            <li><a href="/project/step1.html"><em>1</em> - Правила</a></li>
            <li class="active"><em>2</em> - Проект</li>
            <li><a href="#" onclick="return false;"><em>3</em> - Необходимая сумма</a></li>
            <?if(!$user->filled):?>
            <li><a href="#" onclick="return false;"><em>4</em> - Личная информация</a></li>
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
                    <?=$form->input('title',array('placeholder'=>'Название проекта','maxlength'=>'60'))?>
                    <i class="limit"><span><?=mb_strlen($model->title,'UTF-8')?></span>/60</i>
                </div>
                    <div class="info">Название проекта должно быть простым и запоминающимся. Оно должно отражать суть.</div>
                <div class="field" style="position: relative;z-index:10">
                    <ul id="tags">
                        <?foreach($tags as $tag):?>
                        <li><?=$tag->tag_id()->tag?></li>
                        <?endforeach;?>
                    </ul>
                </div>
                <div class="field">
                    <?=$form->textarea('short_content',array('placeholder'=>'Краткое описание','maxlength'=>'255'))?>
                    <i class="limit"><span><?=mb_strlen($model->short_content,'UTF-8')?></span>/255</i>
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
                <div class="field">
                    <div><strong>Ссылки на проект на других источниках</strong></div>
                    <div><?=$form->input('links[]',array('placeholder'=>'Vkontakte','value'=>X3_Html::encode($links[0])))?></div>
                    <div><?=$form->input('links[]',array('placeholder'=>'Facebook','value'=>X3_Html::encode($links[1])))?></div>
                    <div><?=$form->input('links[]',array('placeholder'=>'Twitter','value'=>X3_Html::encode($links[2])))?></div>
                    <div><?=$form->input('links[]',array('placeholder'=>'Мой Мир','value'=>X3_Html::encode($links[3])))?></div>
                    <div><?=$form->input('links[]',array('placeholder'=>'YouTube','value'=>X3_Html::encode($links[4])))?></div>
                    <div><?=$form->input('links[]',array('placeholder'=>'Google+','value'=>X3_Html::encode($links[5])))?></div>
                </div>
                <div class="field">
                    <button type="submit">Далее</button>
                </div>
                <?=$form->end();?>
            </div>
        </div>
    </div>
</div>
<?=X3_Widget::run('@views:_widgets:about_quick.php', array(), array('cache'=>!X3_DEBUG))?>
<script>
    var availableTags = <?=json_encode($ttags)?>;
</script>