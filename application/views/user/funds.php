<?php
if(X3::user()->id === $user->id)
    $labels = array(
        "Мой профиль",
        "Мои проекты",
        "Мои вложения",
        "Мне вложили",
        "Сообщения мне",
    );
else
    $labels = array(
        "Профиль",
        "Проекты",
        "Инвестиции",
        "Вложения",
    );
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
<?=$this->renderPartial('_user_head',array('user'=>$user));?>
<div class="body" style="position: relative">
    <div class="tabs">
        <ul>
            <li><a href="/user/<?=$user->id?>/"><?=$labels[0]?></a></li>
            <li><a href="/user/<?=$user->id?>/projects.html"><?=$labels[1]?></a></li>
            <li><a href="/user/<?=$user->id?>/invested.html"><?=$labels[2]?></a></li>
            <li><a href="/user/<?=$user->id?>/investments.html"><?=$labels[3]?></a></li>
            <?if(X3::user()->id == $user->id):?>
            <li><a href="/user/<?=$user->id?>/messages.html"><?=$labels[4]?></a></li>
            <?endif;?>
        </ul>
    </div>
    <div class="clearfix" style="height:30px;">&nbsp;</div>
    <div class="right-bar item-show-bar">
        <div class="pane">
            <div class="pane-cont">
                <h3>Условия</h3>
                <p><?=SysSettings::getValue('Conditions.User','text','Условия пополнения личного счета','Общие','Условия пополнения личного счета')?></p>
            </div>
        </div>
    </div>
    <div class="item-body">
        <div class="pane">
            <div class="pane-cont">
                <h3>Выберите систему оплаты</h3><br/>
                <a href="/user/<?=$user->id?>/funds?type=qiwi" class="pay" title="Пополнение через Qiwi">
                    <img src="/images/qiwi.png" alt="QIWI" />
                </a>
                <a href="/user/<?=$user->id?>/funds?type=visa" class="pay" title="Оплата с помощью карточек Visa">
                    <img src="/images/visa.png" width="158" alt="VISA" />
                </a>
                <div class="hr">&nbsp;</div>
                <a class="grey_link" href="/user/<?=$user->id?>/">Отменить</a>
            </div>
        </div>
    </div>
    <div class="clear" style="height: 120px">&nbps;</div>
</div>