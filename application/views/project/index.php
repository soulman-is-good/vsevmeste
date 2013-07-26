<?
$sorts = array(
    'popular'=>X3::translate('Популярные'),
    'weekly'=>X3::translate('Недавно начатые'),
    'ending'=>X3::translate('Заканчивающиеся'),
    'cheap'=>X3::translate('Малые'),
    'almost'=>X3::translate('Почти собрали на'),
);
?>
<div class="body">
    <div class="clearfix" style="height:40px;">&nbsp;</div>
    <div style="float:right;width:210px;">
        <h2 class="title_subcont">По категориям</h2>
        <ul class="cats">
            <?foreach($cats as $cat):?>
            <?if($cat->id === $category->id):?>
            <li><span><?=X3_Html::encode($cat->title)?></span></li>
            <?else:?>
            <li><a href="/projects-<?=$cat->name?>/<?=X3::request()->getRequest('sort')?>"><?=X3_Html::encode($cat->title)?></a></li>
            <?endif;?>
            <?endforeach;?>
        </ul>
        <div style="font-size:0px;line-height:0;height:20px">&nbsp;</div>
        <h2 class="title_subcont">Показать</h2>
        <ul class="cats">
            <?if($sort === 'popular'):?>
            <li><span><?=X3::translate('Популярные');?></span></li>
            <?else:?>
            <li><a href="/projects<?=$category!==null?'-'.$category->name:''?>/popular/"><?=X3::translate('Популярные');?></a></li>
            <?endif;?>
            <?if($sort === 'weekly'):?>
            <li><span><?=X3::translate('Недавно начатые');?></span></li>
            <?else:?>
            <li><a href="/projects<?=$category!==null?'-'.$category->name:''?>/weekly/"><?=X3::translate('Недавно начатые');?></a></li>
            <?endif;?>
            <?if($sort === 'ending'):?>
            <li><span><?=X3::translate('Заканчивающиеся');?></span></li>
            <?else:?>
            <li><a href="/projects<?=$category!==null?'-'.$category->name:''?>/ending/"><?=X3::translate('Заканчивающиеся');?></a></li>
            <?endif;?>
            <?if($sort === 'cheap'):?>
            <li><span><?=X3::translate('Малые проекты');?></span></li>
            <?else:?>
            <li><a href="/projects<?=$category!==null?'-'.$category->name:''?>/cheap/"><?=X3::translate('Малые проекты');?></a></li>
            <?endif;?>
            <?if($sort === 'almost'):?>
            <li><span><?=X3::translate('Почти собрали');?></span></li>
            <?else:?>
            <li><a href="/projects<?=$category!==null?'-'.$category->name:''?>/almost/"><?=X3::translate('Почти собрали');?></a></li>
            <?endif;?>
            <?/*<li><a href="/projects<?=isset($category)?$category:''?>/curator/"><?=X3::translate('Куратор Страницы');?></a></li>*/?>
        </ul>
    </div>
    <h1 class="title_cont" style="float: none;margin-left: 0;">
        <?if($category!==null):?>
        <?=$sort==null?'<a href="/projects/">'.X3::translate('Проекты'):$sorts[$sort].' <a href="/projects/">'.mb_strtolower(X3::translate('Проекты'))?></a> / <?=$category->title?>
        <?else:?>
        <?=$sort==null?X3::translate('Проекты'):$sorts[$sort].' '.mb_strtolower(X3::translate('Проекты'))?>
        <?endif;?>
    </h1>
    <div class="main_projects_cont" style="width:715px">
        <?foreach($models as $model):?>
        <?=$this->renderPartial('@views:project:_project_item.php',array('model'=>$model));?>
        <?endforeach;?>
    </div>
    <?=$paginator?>
    <div class="clear" style="height:80px;">&nbsp;</div>
</div>
