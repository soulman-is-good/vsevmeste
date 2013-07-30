<?php
/* @var $model Project_Invest */
?>
<div class="project-event">
    <img class="event-avatar" src="<?=$model->user_id()->getAvatar()?>" />
    <div class="project-event-content">
        <strong><a href="/user/<?=$model->user_id()->id?>/projects.html" class="grey_link"><?=$model->user_id()->fullName?></a></strong> <i>вложено <?=date('d.m.Y H:i:s',$model->created_at)?></i>
        <?if(isset($show) && $show):?>
        <p>
        Вложил <b><?=  number_format($model->amount,0,'.',' ')?></b> тенге в проект <a href="/<?=$model->project_id()->name?>-project/">"<?=$model->project_id()->title?>"</a>
        </p>
        <?else:?>
        <br/>
        <?=  number_format($model->amount,0,'.',' ')?> тенге
        <?endif;?>
    </div>
</div>
<div class="hr">&nbsp;</div>
