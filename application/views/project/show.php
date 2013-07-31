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
        <?if($interests->count()>0):?>
        <div class="invest-label">Вложить в проект:</div>
        <?foreach($interests as $interest):?>
        <a class="pane" href="/<?=$model->name?>-project/i<?=  sprintf("%010d",$interest->id)?>.html" style="margin-bottom:20px">
            <div class="pane-cont">
                <div class="price"><span><?=X3_Html::encode($interest->sum)?></span> тенге</div>
                <div style="margin-top:20px;color:#000;"><?=X3_Html::encode($interest->title)?></div>
                <div class="hr">&nbsp;</div>
                <i style="color:#999"><?=X3_Html::encode($interest->notes)?></i>
                <div style="color:#000;padding:5px 0">Доставка до: <?=I18n::date($interest->deliver_at)?></div>
                <div style="color:#000;padding:0px 0px 5px 0px">Осталось: <b><?=$interest->limit - $interest->bought?>/<?=$interest->limit?></b></div>
            </div>
        </a>
        <?endforeach;?>
        <?endif;?>
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
                <?=$model->full_content?>
            </div>
        </div>
    </div>
    <div class="clear" style="height: 120px">&nbps;</div>
</div>
