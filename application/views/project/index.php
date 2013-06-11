<div class="body">
    <div class="clearfix" style="height:40px;">&nbsp;</div>
    <div style="float:right;width:230px;">
        <h2 class="title_subcont">По категориям</h2>
        <ul class="cats">
            <?foreach($cats as $cat):?>
            <li><a href="hello"><?=$cat->title?></a></li>
            <?endforeach;?>
        </ul>
    </div>
    <h1 class="title_cont" style="float: none">Дизайн</h1>
    <div class="main_projects_cont" style="margin-left: 35px;width:715px">
        <?foreach($models as $model):?>
        <?=$this->renderPartial('@views:project:_project_item.php',array('model'=>$model));?>
        <?endforeach;?>
    </div>
    <?=$paginator?>
    <div class="clear" style="height:80px;">&nbsp;</div>
</div>