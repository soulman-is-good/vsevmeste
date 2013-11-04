<?php
$sum = $interest !== null?$interest->sum:$data['amount'];
$user = User::getByPk(X3::user()->id);
?>
<style>
    .pay img {
        border: 4px solid #ccc;
        border-radius: 15px;
    }
    .pay:hover img {
        border: 4px solid #37a304;
    }
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
            <li><a href="/<?=$model->name?>-project/investments.html">Вложения <i><?=Project_Invest::num_rows(array('project_id'=>$model->id))?></i></a></li>
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
            <?if($interest !== null):?>
                <h3>Вы хотите вложить в проект <?=  number_format($interest->sum,0,'.',' ')?> тенге</h3>
                <br/>
                Ваш интерес: <b><?=$interest->title?></b>
                <div class="hr">&nbsp;</div>
            <?else:?>
                <h3>Вы хотите вложить в проект <?=  number_format($data['amount'],0,'.',' ')?> тенге</h3>
                <div class="hr">&nbsp;</div>
            <?endif;?>
                <h3>Выберите систему оплаты</h3><br/>
                <a href="#qiwi" class="pay" onclick="return false;" title="Оплата через Qiwi">
                    <img style="opacity:0.5" src="/images/qiwi.png" alt="QIWI" />
                </a>
                <a href="/epay/<?=$data['id']?>" class="pay" title="Оплата через epay">
                    <img src="/images/epay.png" alt="EPAY" />
                </a>
                <?if($user->money>=$sum):?>
                <a href="/wallet/<?=$data['id']?>" class="pay" title="На вашем счету <?=(int)$user->money?> тенге">
                    <img width="150" src="/images/wallet-icon.png" alt="Личный счет" />
                </a>
                <?endif;?>
                <div class="hr">&nbsp;</div>
                <a class="grey_link" href="/<?=$model->name?>-project/">Отменить</a>
            </div>
        </div>
    </div>
    <div class="clear" style="height: 120px">&nbps;</div>
</div>