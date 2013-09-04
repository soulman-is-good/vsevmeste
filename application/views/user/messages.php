<?php
if(X3::user()->id === $user->id)
    $labels = array(
        "Мой профиль",
        "Мои проекты",
        "Мои вложения",
        "Мне вложили",
        "Cообщения мне",
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
<style>.admin-links {position: absolute;right:0px;top:0px;    }    .admin-links a {}.project-event .admin-links{opacity:0;transition: opacity 0.5s;-webkit-transition: opacity 0.5s;-moz-transition: opacity 0.5s;    }    .project-event:hover .admin-links{opacity:1;transition: opacity 0.5s;-webkit-transition: opacity 0.5s;-moz-transition: opacity 0.5s;    }    .project-event {position:relative;min-height:100px;    }    .event-avatar {float:left;border-radius: 10px;-moz-border-radius: 10px;-webkit-border-radius: 10px;    }    .project-event-content {margin-left: 115px;    }    .project-event-content a.grey_link {font-size:16px;font-style: italic;color:#000;}    .project-event-content i {font-style: italic;color:#999;font-size: 13px;padding-left:5px;    }.project-event-content p {margin-top:10px;text-align: justify;}</style>
<div class="body" style="position: relative">
    <div class="tabs">
        <ul>
            <li><a href="/user/<?=$user->id?>/"><?=$labels[0]?></a></li>
            <li><a href="/user/<?=$user->id?>/projects.html"><?=$labels[1]?></a></li>
            <li><a href="/user/<?=$user->id?>/invested.html"><?=$labels[2]?></a></li>
            <li><a href="/user/<?=$user->id?>/investments.html"><?=$labels[3]?></a></li>
            <li class="active"><?=$labels[4]?></li>
        </ul>
    </div>
    <div class="clearfix" style="height:30px;">&nbsp;</div>
    <div class="main_projects_cont" style="width:715px">
        <?foreach($models as $model):?>
        <div class="project-event <?=$model->status?> user-message<?=$model->status==0?' unread':''?>" data-mid="<?=$model->id?>">
            <img class="event-avatar" src="<?=$model->from_user_id()->getAvatar()?>" />
            <div class="project-event-content">
                <strong><a href="/user/<?=$model->from_user_id?>.html" class="grey_link"><?=$model->from_user_id()->fullName?></a></strong> <i>добавлено <?=date('d.m.Y H:i:s',$model->created_at)?></i>
                <p style="margin:5px 0;padding:0;"><strong style="border-bottom: 1px solid #ccc;"><?=X3_Html::encode($model->title)?></strong></p>
                <p style="padding:0;margin: 0 5px"><?=nl2br(X3_Html::encode($model->text))?></p>
            </div>
        </div>
        <div class="hr">&nbsp;</div>
        <?endforeach;?>
    </div>
    <?=$paginator?>
    <div class="clear" style="height: 120px">&nbps;</div>
</div>