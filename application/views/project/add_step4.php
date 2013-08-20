<?php
$form = new Form($model);
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
            <li><a href="/project/step2.html"><em>2</em> - Проект</a></li>
            <li><a href="/project/step3.html"><em>3</em> - Необходимая сумма</a></li>
            <li class="active"><em>4</em> - Личная информация</li>
        </ul>
    </div>
    <div class="pane" style="margin:30px 0;width:615px">
        <div class="pane-cont">
            <div class="form">
                <?if($model->getTable()->getErrors()):?>
                <?=  X3_Html::errorSummary($model);?>
               <?endif;?> 
                <?=$form->start();?>
                <h3>Заполните инормацию о себе</h3>
                <div class="field">
                    <?=$form->input('name',array('placeholder'=>'Укажите ваше имя'))?>
                </div>
                <div class="field">
                    <?=$form->input('surname',array('placeholder'=>'Укажите вашу фамилию'))?>
                </div>
                <div class="field">
                    <?=$form->input('date_of_birth',array('value'=>date('d.m.Y',$model->date_of_birth==0?time()-567648000:$model->date_of_birth)));//~18 years?>
                    <div class="info">
                        Укажите дату вашего рождения
                    </div>
                </div>
                <div class="field">
                    <?=$form->select('city_id')?>
                    <div class="info">Выберите ваш город</div>
                </div>
                <div class="field">
                    <?=$form->input('debitcard',array('placeholder'=>'Укажите номер вашей банковской карты'))?>
                    <div class="info">Мы переведем на нее деньги, которые вы соберете с проекта, за минусом 9% комиссии.</div>
                </div>
                <h3>Если у вас есть компания, укажите здесь данные о ней</h3>
                <div class="field">
                    <div><?=$form->input('company_name',array('placeholder'=>'Название компании'))?></div>
                </div>
                <div class="field">
                    <div><?=$form->input('company_bin',array('placeholder'=>'ИИН/БИН компании'))?></div>
                </div>
                <div class="field" style="margin-top:20px">
                    <?/*<button name="draft" type="submit">Сохранить в черновик</button>&nbsp;*/?><button name="send" type="submit">Опубликовать проект</button>
                </div>
                <?=$form->end();?>
            </div>
        </div>
    </div>
</div>
<?=X3_Widget::run('@views:_widgets:about_quick.php', array(), array('cache'=>!X3_DEBUG))?>