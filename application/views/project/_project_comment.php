<?php
/* @var $model Project_Event */
?>
<div class="project-event">
    <?if($model->user_id == X3::user()->id || X3::user()->isAdmin()):?>
    <div class="admin-links">
        <a href="/project_Comment/delete/id/<?=$model->id?>" title="Удалить"><img src="/images/delete_dis.png" alt="X" /></a>
    </div>
    <?endif;?>
    <img class="event-avatar" src="<?=$model->user_id()->getAvatar()?>" />
    <div class="project-event-content">
        <strong><a href="/user/<?=$model->user_id()->id?>/projects.html" class="grey_link"><?=$model->user_id()->fullName?></a></strong> <i>добавлено <?=date('d.m.Y H:i:s',$model->created_at)?></i>
        <br/><?=$model->content?>
    </div>
</div>
<div class="hr">&nbsp;</div>
