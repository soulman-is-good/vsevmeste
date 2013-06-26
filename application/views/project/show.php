<?=$this->renderPartial('_item_head',array('model'=>$model));?>
<div class="body" style="position: relative">
    <div class="tabs">
        <ul>
            <li class="active">Проект</li>
            <li><a href="/<?=$model->name?>-project/events.html">События <i>13</i></a></li>
            <li><a href="/<?=$model->name?>-project/comments.html">Комментарии <i>13</i></a></li>
            <?/*<li><a href="/<?=$model->name?>-project/gallery.html">Вложения <i>13</i></a></li>*/?>
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
                        <li>Цель <b><?=$model->needed_sum?></b> тенге</li>
                        <li><?=$model->getTimeLeft()?></li>
                    </ul>
                </div>
            </div>
            <a href="#" class="invest_big">Вложить в проект</a>
        </div>
        <div class="invest-label">Вложить в проект:</div>
        <a class="pane" href="#">
            <div class="pane-cont">
                <div class="price"><span>100</span> тенге</div>
                <div style="margin-top:20px;color:#000;">Просто так поддержать проект</div>
                <div class="hr">&nbsp;</div>
                <i style="color:#999">Мы вышлем вам спасибо на почту</i>
                <div style="color:#000;padding:5px 0">Доставка до: 13 марта 2013</div>
                <div style="color:#000;padding:0px 0px 5px 0px">Осталось: <b>22</b></div>
            </div>
        </a>
    </div>
    <div class="item-body">
        <img width="100%" src="/images/03.jpg" alt="" />
        <div class="share">
            <b>Поделиться ссылкой</b> <input class="search_field" type="text" value="http://<?=$_SERVER['HTTP_HOST']?>/<?=$model->name?>-project/" />
            <div style="display:inline-block" class="yashare-auto-init" data-yashareL10n="ru"
             data-yashareType="none" data-yashareQuickServices="vkontakte,facebook,twitter,gplus,odnoklassniki,moimir"

            ></div> 
        </div>
    </div>
    <div class="clear" style="height: 120px">&nbps;</div>
</div>
<script type="text/javascript" src="//yandex.st/share/share.js"
charset="utf-8"></script>