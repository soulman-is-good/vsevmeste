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
            <li><a href="/<?=$model->name?>-project/">Проект</a></li>
            <li class="active">События</li>
            <li><a href="/<?=$model->name?>-project/comments.html">Комментарии <i><?=Project_Comments::num_rows(array('project_id'=>$model->id));?></i></a></li>
            <li><a href="/<?=$model->name?>-project/investments.html">Вложения <i><?=Project_Invest::num_rows(array('project_id'=>$model->id))?></i></a></li>
        </ul>
    </div>
    <div class="clearfix" style="height:30px;">&nbsp;</div>
    <div class="right-bar item-show-bar">
        <div class="pane">
            <div class="pane-cont">
                <div class="price"><span><?=  number_format($model->current_sum,0,'.',' ')?></span> тенге</div>
                <div class="finish_cont">
                    <div class="finish" style="width:<?=$model->getPercentDone()?>%">&nbsp;</div>
                </div>
                <div class="trgndtime">
                    <ul>
                        <li>Цель <b><?=X3_Html::encode($model->needed_sum)?></b> тенге</li>
                        <li><?=$model->getTimeLeft()?></li>
                    </ul>
                </div>
            </div>
            <a href="#" class="invest_big">Вложить в проект</a>
        </div>
        <?if($interests->count()>0):?>
        <div class="invest-label">Вложить в проект:</div>
        <?foreach($interests as $interest):?>
        <a class="pane" href="/<?=$model->name?>-project/i<?=  sprintf("%010d",$interest->id)?>.html" style="margin-bottom:20px">
            <div class="pane-cont">
                <div class="price"><span><?=X3_Html::encode($interest->sum)?></span> тенге</div>
                <div style="margin-top:20px;color:#000;"><?=X3_Html::encode($interest->title)?></div>
                <div class="hr">&nbsp;</div>
                <i style="color:#999"><?=X3_Html::encode($interest->notes)?></i>
                <div style="color:#000;padding:5px 0">Доставка до: <?=I18n::date($interest->deliver_at)?></div>
                <div style="color:#000;padding:0px 0px 5px 0px">Осталось: <b><?=$interest->left?></b></div>
            </div>
        </a>
        <?endforeach;?>
        <?endif;?>
    </div>
    <div class="item-body">
        <div class="pane">
            <div class="pane-cont">
                <?php
                if($models->count()>0):
                foreach ($models as $event) {
                    echo $this->renderPartial('_project_event',array('model'=>$event));
                }else:?>
                <?=X3::translate('Событий нет')?>.
                <?endif;?>
                <?if($model->user_id == X3::user()->id):?>
                <?php
                $form = new Form('Event');
                echo $form->start();
                echo X3_Html::form_tag('input', array('type'=>'hidden','name'=>'token','value'=>X3::user()->etoken));
                ?>
                <div class="hr">&nbsp;</div>
                <p>Добавьте событие:</p>
                <div class="form">
                    <div class="field">
                        <textarea name="eventtext" id="eventtext" style="width:550px;height:96px"></textarea>
                    </div>
                    <div class="field">
                        <button type="submit">Добавить</button>
                    </div>
                </div>
                <?=$form->end();?>
                <?endif;?>
            </div>
        </div>
    </div>
    <div class="clear" style="height: 120px">&nbps;</div>
</div>