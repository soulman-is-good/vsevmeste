<?php
$form = new Form($model);
?>
<div class="item-head">
    <div class="item-head-body">
        <h1 style="font-size:30px;margin:0 0 0 30px">Редактирование профиля</h1>
    </div>
</div>
<div class="body project-add" style="position: relative">
    <div class="pane" style="margin:30px 0;width:615px">
        <div class="pane-cont">
            <div class="form">
                <?if($model->getTable()->getErrors()):?>
                <?=  X3_Html::errorSummary($model);?>
               <?endif;?> 
                <?=$form->start();?>
                <h3>Заполните инормацию о себе</h3>
                <div class="field">
                    <div><strong>Изображение на аватар</strong> <button id="upl" type="button">Загрузить</button> <i id="str"></i> </div>
                    <div id="blah"></div>
                    <?=$form->file('image',array('style'=>'position:absolute;left:-9999px;','id'=>'p-file'))?>
                </div>
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
                <h3>Смена пароля</h3>
                <div class="field">
                    <div><?=$form->input('password_old',array('placeholder'=>'Текущий пароль','name'=>'Change[password_old]'))?></div>
                </div>
                <div class="field">
                    <div><?=$form->input('password_new',array('placeholder'=>'Новый пароль','name'=>'Change[password_new]'))?></div>
                </div>
                <div class="field">
                    <div><?=$form->input('password_repeat',array('placeholder'=>'Повторите новый пароль','name'=>'Change[password_repeat]'))?></div>
                </div>
                <div class="field" style="margin-top:20px">
                    <button name="send" type="submit">Сохранить</button>
                </div>
                <?=$form->end();?>
            </div>
        </div>
    </div>
</div>
