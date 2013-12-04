<div style="height:20px">&nbsp;</div>
<div class="pane">
    <div class="pane-cont" style="padding: 19px;">
        <h3>Автор проекта</h3>
        <div class="hr">&nbsp;</div>
        <img class="p-avatar" src="<?= $model->user_id()->getAvatar() ?>" />
        <div class="project-p-content">
            <strong><a href="/user/<?= $model->user_id()->id ?>/projects.html" class="grey_link"><?= $model->user_id()->fullName ?></a></strong><br/>
            <i style="padding:0px; font-size: 11px">последний визит <?= date('d/m/Y', $model->user_id()->lastbeen_at) ?></i>
            <div class="city" style="margin:8px 0px 0px 0px">
                <img src="/images/location.png" alt="">
                <i style="padding:0px; font-size: 11px"><?=$model->user_id()->city_id()->title?></i>
            </div>
            <?if(($c = Project_Invest::num_rows(array('user_id'=>$model->user_id,'project_id'=>$model->id,'status'=>  Project_Invest::STATUS_SUCCESS)))>0):?>
            <div class="city" style="margin:0px">
                <img src="/images/page.png" alt="" width="9" />
                <i style="padding:0px; font-size: 11px">Поддержал <?= $c . " " . X3_String::create($c)->numeral($c, array('проект', "проекта", "проектов")) ?></i>
            </div>
            <?endif;?>
            <?if(($c = Project_Invest::num_rows(array('user_id'=>X3::user()->id,'project_id'=>$model->id,'status'=>  Project_Invest::STATUS_SUCCESS)))>0):?>
            <div class="city" style="margin:0px">
                <img src="/images/page.png" alt="" width="9" />
                <i style="padding:0px; font-size: 11px"><a href="/<?=$model->name?>-project/comments.html">Написать сообщение</a></i>
            </div>
            <?endif;?>
        </div>
        <div class="clear">&nbsp;</div>
    </div>
</div>