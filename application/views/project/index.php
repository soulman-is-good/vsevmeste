<div class="body">
    <div class="clearfix" style="height:40px;">&nbsp;</div>
    <div style="float:right;width:230px;">
        <h2 class="title_subcont">По категориям</h2>
        <ul class="cats">
            <?foreach($cats as $cat):?>
            <?if($cat->id === $category->id):?>
            <li><span><?=$cat->title?></span></li>
            <?else:?>
            <li><a href="/projects-<?=$cat->name?>/<?=X3::request()->getRequest('sort')?>"><?=$cat->title?></a></li>
            <?endif;?>
            <?endforeach;?>
        </ul>
        <div style="font-size:0px;line-height:0;height:20px">&nbsp;</div>
        <h2 class="title_subcont">Показать</h2>
        <ul class="cats">
            <li><a href="/projects<?=$category!==null?'-'.$category->name:''?>/popular/"><?=X3::translate('Популярные');?></a></li>
            <li><a href="/projects<?=$category!==null?'-'.$category->name:''?>/weekly/"><?=X3::translate('Недавно начатые');?></a></li>
            <li><a href="/projects<?=$category!==null?'-'.$category->name:''?>/ending/"><?=X3::translate('Заканчивающиеся');?></a></li>
            <li><a href="/projects<?=$category!==null?'-'.$category->name:''?>/cheap/"><?=X3::translate('Малые проекты');?></a></li>
            <li><a href="/projects<?=$category!==null?'-'.$category->name:''?>/almost/"><?=X3::translate('Почти собрали');?></a></li>
            <?/*<li><a href="/projects<?=isset($category)?$category:''?>/curator/"><?=X3::translate('Куратор Страницы');?></a></li>*/?>
        </ul>
    </div>
    <h1 class="title_cont" style="float: none">
        <?if($category!==null):?>
        <a href="/projects/">Проекты</a> / <?=$category->title?>
        <?else:?>
        Проекты
        <?endif;?>
    </h1>
    <div class="main_projects_cont" style="margin-left: 35px;width:715px">
        <?foreach($models as $model):?>
        <?=$this->renderPartial('@views:project:_project_item.php',array('model'=>$model));?>
        <?endforeach;?>
    </div>
    <?=$paginator?>
    <div class="clear" style="height:80px;">&nbsp;</div>
</div>