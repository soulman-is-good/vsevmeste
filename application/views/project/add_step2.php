<?php
$form = new Form($model);
$form1 = new Form(new Project_Interest);
$days = "";
echo $model->end_at;
if($model->end_at > 0) {
    $days = ceil(($model->end_at - $model->created_at)/86400);
}
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
            <?if(!$user->filled):?>
            <li><a href="#" onclick="return false;"><em>3</em> - Личная информация</a></li>
            <?endif;?>
        </ul>
    </div>
    <div class="pane" style="margin:30px 0;width:615px">
        <div class="pane-cont">
            <div class="form">
                <?if(!empty($errors)):?>
                <div class="errors">
                <?  foreach ($errors as $errs):?>
                    <?foreach($errs as $ers):?>
                    <div><?=$ers[0]?></div>
                    <?endforeach;?>
                <?endforeach;?>
                </div>
                <?endif;?>
                <?=$form->start();?>
                <h3>Необходимая сумма и сроки</h3>
                <div class="field">
                    <?=$form->input('needed_sum',array('placeholder'=>'Укажите необходимую сумму для вашей цели'))?>
                    <div class="info">
                        После завершения времени сбора денег на ваш проект, мы берем 9% с собранной суммы, поэтому учитывайте данную коммисию заранее
                    </div>
                </div>
                <div class="field">
                    <?=$form->input('end_at',array('placeholder'=>'Укажите за сколько дней вы хотите получить вложения в проект','value'=>$days))?>
                </div>
                <h3>Чем вы наградите ваших вкладчиков при вложении...</h3>
                <div id="interests">
                    <?foreach($interests as $i=>$interest):$ierrors = $interest->getTable()->getErrors();?>
                    <div class="interest-add interest-<?=$i?>">
                        <div class="field">
                            <?if(!$interest->getTable()->getIsNewRecord()):?>
                            <input type="hidden" name="Project_Interest[][id]" id="Project_Interest_<?=$i?>_id" value="<?=$interest->id?>"/>
                            <?endif;?>
                            <input placeholder="Сумма вложения (тенге)" class="<?=$interest->getTable()->hasError('sum')?'error':'';?>" type="text" name="Project_Interest[][sum]" id="Project_Interest_<?=$i?>_sum" value="<?=$interest->sum?>"/> 
                        </div>
                        <div class="field">
                            <input placeholder="Название интереса вкладчика" class="<?=$interest->getTable()->hasError('title')?'error':'';?>" type="text" name="Project_Interest[][title]" id="Project_Interest_<?=$i?>_title" value="<?=$interest->title?>"/>
                        </div>
                        <div class="field">
                            <textarea placeholder="Описание интереса вкладчика" class="<?=$interest->getTable()->hasError('notes')?'error':'';?>" rows="7" cols="30" name="Project_Interest[][notes]" id="Project_Interest_<?=$i?>_notes"><?=$interest->notes?></textarea>
                        </div>
                        <div class="field">
                            <input placeholder="Количество интересов" type="text" name="Project_Interest[][limit]" class="<?=$interest->getTable()->hasError('limit')?'error':'';?>" id="Project_Interest_<?=$i?>_limit" value="<?=$interest->limit?>"/>
                        </div>
                        <div class="field">
                            <div class="info">
                                Дата доставки интереса вкладчику
                                <input style="width:257px;margin:0 7px" type="text" name="Project_Interest[][deliver_at]" class="<?=$interest->getTable()->hasError('deliver_at')?'error':'';?>" value="<?=date('d.m.Y',$interest->deliver_at)?>" id="Project_Interest_<?=$i?>_deliver_at">
                            </div>
                        </div>
                        <div class="field">
                            <button type="button" class="add-interest">Добавить еще интерес</button>
                            <?if($i>0):?>
                            <button type="button" class="red remove-interest">Удалить</button>
                            <?endif?>
                        </div>
                    </div>
                    <?endforeach;?>
                </div>
                <?if(!$user->filled):?>
                <div class="field" style="margin-top:20px">
                    <button name="next" type="submit">Далее</button>
                </div>
                <?else:?>
                <div class="field" style="margin-top:20px">
                    <?/*<button name="draft" type="submit">Сохранить в черновик</button>&nbsp;*/?><button name="send" type="submit">Опубликовать проект</button>
                </div>
                <?endif;?>
                <?=$form->end();?>
            </div>
        </div>
    </div>
</div>
<script type="text/html" id="interest-tmpl">
<div class="interest-add interest-{index}">
    <div class="field">
        <input placeholder="Сумма вложения (тенге)" type="text" name="Project_Interest[{index}][sum]" id="Project_Interest_{index}_sum" value=""/>                    </div>
    <div class="field">
        <input placeholder="Название интереса вкладчика" type="text" name="Project_Interest[{index}][title]" id="Project_Interest_{index}_title" value=""/>                    </div>
    <div class="field">
        <textarea placeholder="Описание интереса вкладчика" rows="7" cols="30" name="Project_Interest[{index}][notes]" id="Project_Interest_{index}_notes"></textarea>                    </div>
    <div class="field">
        <input placeholder="Количество интересов" type="text" name="Project_Interest[{index}][limit]" id="Project_Interest_{index}_limit" value=""/>                    </div>
    <div class="field">
        <div class="info">
            Дата доставки интереса вкладчику
            <input style="width:257px;margin:0 7px" type="text" name="Project_Interest[{index}][deliver_at]" value="<?=date('d.m.Y')?>" id="Project_Interest_{index}_deliver_at">
        </div>
    </div>
    <div class="field">
        <button type="button" class="add-interest">Добавить еще интерес</button>
        <button type="button" class="red remove-interest">Удалить</button>
    </div>
</div>
</script>
<script>
    var interest_index = <?=count($interests)?>;
    var interest_count = <?=count($interests)?>;
</script>