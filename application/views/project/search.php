<div class="body">
    <div class="clearfix" style="height:40px;">&nbsp;</div>
    <h1 class="title_cont" style="float: none;margin-left: 0;">
        <?=X3::translate('Проекты')?>
    </h1>
    <div class="main_projects_cont" style="width:715px">
        <?foreach($models as $model):?>
        <?=$this->renderPartial('@views:project:_project_item.php',array('model'=>$model));?>
        <?endforeach;?>
    </div>
    <?=$paginator?>
    <div class="clear" style="height:80px;">&nbsp;</div>
</div>