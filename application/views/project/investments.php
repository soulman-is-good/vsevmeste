<style>.admin-links {position: absolute;right:0px;top:0px;    }    .admin-links a {}.project-event .admin-links{opacity:0;transition: opacity 0.5s;-webkit-transition: opacity 0.5s;-moz-transition: opacity 0.5s;    }    .project-event:hover .admin-links{opacity:1;transition: opacity 0.5s;-webkit-transition: opacity 0.5s;-moz-transition: opacity 0.5s;    }    .project-event {position:relative;min-height:100px;    }    .event-avatar {float:left;border-radius: 10px;-moz-border-radius: 10px;-webkit-border-radius: 10px;    }    .project-event-content {margin-left: 115px;    }    .project-event-content a.grey_link {font-size:16px;font-style: italic;color:#000;}    .project-event-content i {font-style: italic;color:#999;font-size: 13px;padding-left:5px;    }.project-event-content p {margin-top:10px;text-align: justify;}</style>
<?=$this->renderPartial('_item_head',array('model'=>$model));?>
<div class="body" style="position: relative">
    <div class="tabs">
        <ul>
            <li><a href="/<?= $model->name ?>-project/">Проект</a></li>
            <li><a href="/<?= $model->name ?>-project/events.html">События <i><?=Project_Event::num_rows(array('project_id'=>$model->id))?></i></a></li>
            <li><a href="/<?=$model->name?>-project/comments.html">Комментарии <i><?=Project_Comments::num_rows(array('project_id'=>$model->id));?></i></a></li>
            <li class="active">Вложения</li>
        </ul>
    </div>
    <div class="clearfix" style="height:30px;">&nbsp;</div>
    <div class="right-bar item-show-bar">
        <div class="pane">
            <div class="pane-cont">
                <div class="price"><span><?=  number_format($model->current_sum,0,'.',' ')?></span> тенге</div>
                <div class="finish_cont">
                    <div class="finish" style="width:<?=$model->getPercentDone()?>%">&nbsp;</div>
                </div>
                <div class="trgndtime">
                    <ul>
                        <li>Цель <b><?=X3_Html::encode($model->needed_sum)?></b> тенге</li>
                        <li><?=$model->getTimeLeft()?></li>
                    </ul>
                </div>
            </div>
            <a href="/<?=$model->name?>-project/invest.html" class="invest_big">Вложить в проект</a>
        </div>
        <?=  X3_Widget::run('@views:project:_author.php',array('model'=>$model))?>
        <?=  X3_Widget::run('@views:project:_project_interests.php',array('interests'=>$interests,'model'=>$model))?>
    </div>
    <div class="item-body">
        <div class="pane">
            <div class="pane-cont">
                <?php
                if($models->count()>0):
                foreach ($models as $event) {
                    echo $this->renderPartial('_project_investments',array('model'=>$event));
                }else:?>
                <a href="/<?=$model->name?>-project/invest.html"><?=X3::translate('Вложите в проект первым')?>!</a>
                <?endif;?>
            </div>
        </div>
    </div>
    <div class="clear" style="height: 120px">&nbps;</div>
</div>