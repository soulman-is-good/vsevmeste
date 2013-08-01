<?php
$form = new Form($invest);
$uform = null;
$cities = array();
if($theuser != null) {
    $uform = new Form();
    $city = City::get(array('@order'=>'title'));
    foreach($city as $c){
        $cities[$c->title] = $c->title;
    }
}
?>
<style>
    .admin-links {
        position: absolute;
        right:0px;
        top:0px;
    }
    .admin-links a {}
    
    .project-event .admin-links{
        opacity:0;
        transition: opacity 0.5s;
        -webkit-transition: opacity 0.5s;
        -moz-transition: opacity 0.5s;
    }
    .project-event:hover .admin-links{
        opacity:1;
        transition: opacity 0.5s;
        -webkit-transition: opacity 0.5s;
        -moz-transition: opacity 0.5s;
    }
    .project-event {
        position:relative;
        min-height:100px;
    }
    .event-avatar {
        float:left;
        border-radius: 10px;
        -moz-border-radius: 10px;
        -webkit-border-radius: 10px;
    }
    .project-event-content {
        margin-left: 115px;
    }
    .project-event-content a.grey_link {
        font-size:16px;
        font-style: italic;
        color:#000;
    }
    .project-event-content i {
        font-style: italic;
        color:#999;
        font-size: 13px;
        padding-left:5px;
    }
    .project-event-content p {
        margin-top:10px;
        text-align: justify;
    }
</style>
<?=$this->renderPartial('_item_head',array('model'=>$model));?>
<div class="body" style="position: relative">
    <div class="tabs">
        <ul>
            <li><a href="/<?= $model->name ?>-project/">Проект</a></li>
            <li><a href="/<?= $model->name ?>-project/events.html">События <i><?=Project_Event::num_rows(array('project_id'=>$model->id))?></i></a></li>
            <li><a href="/<?=$model->name?>-project/comments.html">Комментарии <i><?=Project_Comments::num_rows(array('project_id'=>$model->id));?></i></a></li>
            <li><a href="/<?=$model->name?>-project/investments.html">Вложения <i><?=Project_Invest::num_rows(array('project_id'=>$model->id,'status'=>'1'))?></i></a></li>
        </ul>
    </div>
    <div class="clearfix" style="height:30px;">&nbsp;</div>
    <div class="right-bar item-show-bar">
        <div class="pane">
            <div class="pane-cont">
                <h3>Условия</h3>
                <p><?=SysSettings::getValue('Conditions','text','Условия','Общие','Условия вложения')?></p>
            </div>
        </div>
    </div>
    <div class="item-body">
        <div class="pane">
            <div class="pane-cont">
        <?if($theuser !== null):?>
                <div class="form">
                <?if($errors!=''):?>
                    <div class="errors">
                    <?=$errors?>
                    </div>
                <?endif;?>
            <?if($interest !== null):?>
                <?=$form->start()?>
                <?=$form->hidden('project_id',array('value'=>$model->id))?>
                <h3>Вы хотите вложить в проект <?=  number_format($interest->sum,0,'.',' ')?> тенге</h3>
                <br/>
                Ваш интерес: <b><?=$interest->title?></b>
                <div class="hr">&nbsp;</div>
                <h3>Информация о доставке</h3>
                <div class="field">
                    <?=$uform->input('',array('placeholder'=>'Введите Ваше имя','name'=>'name'))?>
                </div>
                <div class="field">
                    <?=$uform->input('',array('placeholder'=>'Введите Вашу фамилию','name'=>'surname'))?>
                </div>
                <div class="field">
                    <div class="info">Выберите Ваш город</div>
                    <?=$uform->select($cities,array('name'=>'city'))?>
                </div>
                <div class="field">
                    <div class="info">Адрес доставки</div>
                    <?=$uform->textarea('',array('name'=>'address'))?>
                </div>
                <div class="field">
                    <button type="submit">Вложить</button>
                </div>
                <?=$form->end();?>
            <?else:?>
                <?=$form->start();?>
                <h3>Укажите сумму которую вы хотите вложить в проект</h3>
                <div class="field">
                    <?=$form->input('amount',array('placeholder'=>'Сумма'))?> тенге
                </div>
                <div class="field">
                    <button type="submit">Вложить</button>
                </div>
                <?=$form->end();?>
            <?endif;?>
            <?else:?>
                Для того чтобы вкладывать в проект Вам необходимо <a href="/enter.html">зарегистрироваться</a> или <a href="/enter.html">авторизоваться</a>.
            <?endif;?>
                </div>
            </div>
        </div>
    </div>
    <div class="clear" style="height: 120px">&nbps;</div>
</div>