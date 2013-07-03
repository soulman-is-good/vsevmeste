<?php
/**
 * @var Project_Event $model
 */
?>
<div class="project-event">
    <?if(1):?>
    <div class="admin-links">
        <a href="/project_Event/delete/id/<?=$model->id?>" title="Удалить"><img src="/images/delete_dis.png" alt="X" /></a>
    </div>
    <?endif;?>
    <img class="event-avatar" src="<?=$model->user_id()->getAvatar()?>" />
    <div class="project-event-content">
        <strong><a href="/user/<?=$model->user_id()->id?>/projects.html" class="grey_link"><?=$model->user_id()->fullName?></a></strong> <i>запись добавлена <?=date('d.m.Y',$model->created_at)?></i>
        <p><?=$model->content?></p>
    </div>
</div>
<div class="hr">&nbsp;</div>
