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
?>
<?=$this->renderPartial('_user_head',array('user'=>$user));?>
<div class="body" style="position: relative">
    <div class="tabs">
        <ul>
            <li><a href="/user/<?=$user->id?>/"><?=$labels[0]?></a></li>
            <li class="active"><?=$labels[1]?></li>
            <li><a href="/user/<?=$user->id?>/invested.html"><?=$labels[2]?></a></li>
            <li><a href="/user/<?=$user->id?>/investments.html"><?=$labels[3]?></a></li>
        </ul>
    </div>
    <div class="clearfix" style="height:30px;">&nbsp;</div>
    <div class="main_projects_cont" style="width:715px">
        <?foreach($models as $model):?>
        <?=$this->renderPartial('@views:project:_project_item.php',array('model'=>$model));?>
        <?endforeach;?>
    </div>
    <?=$paginator?>
    <div class="clear" style="height: 120px">&nbps;</div>
</div>