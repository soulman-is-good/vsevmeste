<?php
if(X3::user()->id === $user->id)
    $labels = array(
        "Мой профиль",
        "Мои проекты",
        "Мои вложения",
        "Мне вложили",
    );
else
    $labels = array(
        "Профиль",
        "Проекты",
        "Инвестиции",
        "Вложения",
    );
$total_sum = (int)X3::db()->fetchScalar("SELECT SUM(`current_sum`) FROM `project` WHERE `user_id`='$user->id' AND `status`=1");
$total_clicks = (int)X3::db()->fetchScalar("SELECT SUM(`clicks`) FROM `project` WHERE `user_id`='$user->id' AND `status`=1");
$total_invest = (int)X3::db()->fetchScalar("SELECT SUM(`amount`) FROM `project_invest` WHERE `user_id`='$user->id' AND `status`=1");;
?>
<?=$this->renderPartial('_user_head',array('user'=>$user));?>
<div class="body" style="position: relative">
    <div class="tabs">
        <ul>
            <li class="active"><?=$labels[0]?></li>
            <li><a href="/user/<?=$user->id?>/projects.html"><?=$labels[1]?></a></li>
            <li><a href="/user/<?=$user->id?>/invested.html"><?=$labels[2]?></a></li>
            <li><a href="/user/<?=$user->id?>/investments.html"><?=$labels[3]?></a></li>
        </ul>
    </div>
    <div class="clearfix" style="height:30px;">&nbsp;</div>
    <div class="pane" style="display:inline-block;width:290px;margin-right: 10px;vertical-align: top;">
        <div class="pane-cont">
            <div class="price" style="text-align: center"><span><?=$total_sum?></span> тенге</div>
            <div class="hr">&nbsp;</div>
            <div style="font-size:16px;margin-bottom: 15px;text-align: center"><strong>Вы всего собрали</strong></div>
            <div style="font-size:13px;text-align: center"><i>Всего собрали за все время</i></div>
        </div>
    </div>
    <div class="pane" style="display:inline-block;width:290px;margin-right: 10px;vertical-align: top;">
        <div class="pane-cont">
            <div class="price" style="text-align: center"><span><?=$total_clicks?></span> переходов</div>
            <div class="hr">&nbsp;</div>
            <div style="font-size:16px;margin-bottom: 15px;text-align: center"><strong>Переходов по ссылкам</strong></div>
            <div style="font-size:13px;text-align: center"><i>Копируйте ваши ссылки на проекты и раздавайте всем</i></div>
        </div>
    </div>
    <div class="pane" style="display:inline-block;width:290px;vertical-align: top;">
        <div class="pane-cont">
            <div class="price" style="text-align: center"><span><?=$total_invest?></span> тенге</div>
            <div class="hr">&nbsp;</div>
            <div style="font-size:16px;margin-bottom: 15px;text-align: center"><strong>Ваши вложения</strong></div>
            <div style="font-size:13px;text-align: center"><i>Всего денег вложили в проекты</i></div>
        </div>
    </div>
    <div class="clear" style="height: 120px">&nbps;</div>
</div>