<?php
$form = new Form($model);
$form1 = new Form(new Project_Interest);
?>
<div class="item-head">
    <div class="item-head-body">
        <h1 style="font-size:30px;margin:0 0 0 30px">Размещение проекта</h1>
    </div>
</div>
<div class="body project-add" style="position: relative">
    <div class="tabs" style="top:-79px">
        <ul>
            <li><a href="/project/step1.html"><em>1</em> - Проект</a></li>
            <li class="active"><em>2</em> - Необходимая сумма</li>
        </ul>
    </div>
    <div class="pane" style="margin:30px 0;width:615px">
        <div class="pane-cont">
            <div class="form">
                <?=$form->start();?>
                <h3>Необходимая сумма и сроки</h3>
                <div class="field">
                    <?=$form->input('needed_sum',array('placeholder'=>'Укажите необходимую сумму для вашей цели'))?>
                    <div class="info">
                        После завершения времени сбора денег на ваш проект, мы берем 9% с собранной суммы, поэтому учитывайте данную коммисию заранее
                    </div>
                </div>
                <div class="field">
                    <?=$form->input('end_at')?>
                    <div class="info">
                        Укажите за какое время вы хотите получить вложения в проект
                    </div>
                </div>
                <h3>Чем вы наградите ваших вкладчиков при вложении...</h3>
                <div class="interest-add">
                    <div class="field">
                        <?=$form1->input('sum',array('placeholder'=>'Сумма вложения (тенге)'))?>
                    </div>
                    <div class="field">
                        <?=$form1->input('title',array('placeholder'=>'Название интереса вкладчика'))?>
                    </div>
                    <div class="field">
                        <?=$form1->textarea('notes',array('placeholder'=>'Описание интереса вкладчика'))?>
                    </div>
                    <div class="field">
                        <?=$form1->input('limit',array('placeholder'=>'Количество интересов'))?>
                    </div>
                    <div class="field">
                        <button type="button">Добавить еще интерес</button>
                    </div>
                </div>
                <div class="field" style="margin-top:20px">
                    <button name="draft" type="submit">Сохранить в черновик</button>&nbsp;<button name="send" type="submit">Опубликовать проект</button>
                </div>
                <?=$form->end();?>
            </div>
        </div>
    </div>
</div>