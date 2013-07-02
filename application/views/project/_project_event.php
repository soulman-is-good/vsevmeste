<?php
/**
 * @var Project_Event $model
 */
?>
<div class="project-event">
    <img class="event-avatar" src="<?=$model->user_id()->getAvatar()?>" />
    <div class="project-event-content">
        <strong><a href="/user/<?=$model->user_id()->id?>/projects.html" class="grey_link"><?=$model->user_id()->fullName?></a></strong> <i>запись добавлена <?=date('d.m.Y',$model->created_at)?></i><br/>
        <p><?=$model->content?></p>
    </div>
    <div class="clear">&nbsp;</div>
</div>
<div class="hr">&nbsp;</div>
