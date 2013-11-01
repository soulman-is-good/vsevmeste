<?php
$tags = $model->tags();
?>
<style>
    .b-share-icon_youtube {
    background: url(/js/ckeditor.4/plugins/youtube/images/icon.png) 0 0 no-repeat;
    }
</style>
<?=$this->renderPartial('_item_head',array('model'=>$model));?>
<div class="body" style="position: relative">
    <div class="tabs">
        <ul>
            <li class="active">Проект</li>
            <li><a href="/<?=$model->name?>-project/events.html">События <i><?=Project_Event::num_rows(array('project_id'=>$model->id))?></i></a></li>
            <li><a href="/<?=$model->name?>-project/comments.html">Комментарии <i><?=Project_Comments::num_rows(array('project_id'=>$model->id))?></i></a></li>
            <li><a href="/<?=$model->name?>-project/investments.html">Вложения <i><?=Project_Invest::num_rows(array('project_id'=>$model->id,'status'=>'1'))?></i></a></li>
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
        <div style="height:20px">&nbsp;</div>
        <div class="pane">
            <div class="pane-cont">
                <h3>Автор проекта</h3>
                <div class="hr">&nbsp;</div>
                <img class="p-avatar" src="<?=$model->user_id()->getAvatar()?>" />
                <div class="project-p-content">
                    <strong><a href="/user/<?=$model->user_id()->id?>/projects.html" class="grey_link"><?=$model->user_id()->fullName?></a></strong><br/>
                    <i>последний визит <?=date('d.m.Y H:i:s',$model->user_id()->lastbeen_at)?></i>
                </div>
                <div class="clear">&nbsp;</div>
            </div>
        </div>
        <?=  X3_Widget::run('@views:project:_project_interests.php',array('interests'=>$interests,'model'=>$model))?>
    </div>
    <div class="item-body">
        <?if($model->video!=''):?>
        <object width="600" height="320">
          <param name="movie" value="https://www.youtube.com/v/<?=$model->videoId?>?version=3"></param>
          <param name="allowFullScreen" value="true"></param>
          <param name="allowScriptAccess" value="always"></param>
          <embed src="https://www.youtube.com/v/<?=$model->videoId?>?version=3" type="application/x-shockwave-flash" allowfullscreen="true" allowScriptAccess="always" width="600" height="320"></embed>
        </object>
        <?else:?>
        <img width="100%" src="/uploads/Project/<?=$model->image?>" alt="" />
        <?endif;?>
        <div class="share">
            <b>Поделиться ссылкой</b> <input style="width:235px;" class="search_field" type="text" value="http://<?=$_SERVER['HTTP_HOST']?>/<?=$model->name?>-project/" />
            <div style="display:inline-block" class="yashare-auto-init" data-yashareL10n="ru"
             data-yashareType="none" data-yashareQuickServices="vkontakte,facebook,twitter,gplus,odnoklassniki,moimir"
            ></div> 
        </div>
        <div class="pane">
            <div class="pane-cont">
                <?foreach($tags as $tag):?>
                <a href="/projects/tag/<?=urlencode($tag->tag_id()->tag)?>" class="project-tag"><?=$tag->tag_id()->tag?></a>
                <?endforeach;?>
            </div>
        </div>
        <div class="pane" style="margin-top:20px">
            <div class="pane-cont">
                <?=$model->full_content?>
            </div>
        </div>
        <?if(trim($model->links) !=''):
            if(strpos($model->links,"\n")!==false)
                $links = explode("\n",$model->links);
            else
                $links = explode(" ",$model->links);
        ?>
        <div class="pane" style="margin-top:20px">
            <div class="pane-cont">
                <?foreach($links as $link):if(trim($link)=='') continue;?>
                <a href="<?=X3_Html::encode($link)?>" target="_blank">
                    <?if(strpos($link,'youtu')):?>
                    <span class="b-share-icon b-share-icon_youtube"></span>&nbsp;<?=  X3_Html::encode($link)?><br/><br/>
                    <?endif;?>
                    <?if(strpos($link,'facebook')):?>
                    <span class="b-share-icon b-share-icon_facebook"></span>&nbsp;<?=  X3_Html::encode($link)?><br/><br/>
                    <?endif;?>
                    <?if(strpos($link,'twi')):?>
                    <span class="b-share-icon b-share-icon_twitter"></span>&nbsp;<?=  X3_Html::encode($link)?><br/><br/>
                    <?endif;?>
                    <?if(strpos($link,'moi')):?>
                    <span class="b-share-icon b-share-icon_moimir"></span>&nbsp;<?=  X3_Html::encode($link)?><br/><br/>
                    <?endif;?>
                    <?if(strpos($link,'vk')):?>
                    <span class="b-share-icon b-share-icon_vkontakte"></span>&nbsp;<?=  X3_Html::encode($link)?><br/><br/>
                    <?endif;?>
                </a>
                <?endforeach;?>
            </div>
        </div>
        <?endif;?>
    </div>
    <div class="clear" style="height: 120px">&nbps;</div>
</div>