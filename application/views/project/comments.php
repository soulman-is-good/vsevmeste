<?= $this->renderPartial('_item_head', array('model' => $model)); ?>
<div class="body" style="position: relative">
    <div class="tabs">
        <ul>
            <li><a href="/<?= $model->name ?>-project/">Проект</a></li>
            <li><a href="/<?= $model->name ?>-project/events.html">События <i>13</i></a></li>
            <li class="active">Комментарии</li>
            <? /* <li><a href="/<?=$model->name?>-project/gallery.html">Вложения <i>13</i></a></li> */ ?>
        </ul>
    </div>
    <div class="clearfix" style="height:30px;">&nbsp;</div>
    <div class="right-bar item-show-bar">
        <div class="pane">
            <div class="pane-cont">
                <div class="price"><span><?= number_format($model->current_sum, 0, '.', ' ') ?></span> тенге</div>
                <div class="finish_cont">
                    <div class="finish" style="width:<?= $model->getPercentDone() ?>%">&nbsp;</div>
                </div>
                <div class="trgndtime">
                    <ul>
                        <li>Цель <b><?= $model->needed_sum ?></b> тенге</li>
                        <li><?= $model->getTimeLeft() ?></li>
                    </ul>
                </div>
            </div>
            <a href="#" class="invest_big">Вложить в проект</a>
        </div>
        <? if ($interests->count() > 0): ?>
            <div class="invest-label">Вложить в проект:</div>
            <? foreach ($interests as $interest): ?>
                <a class="pane" href="/<?= $model->name ?>-project/i<?= sprintf("%010d", $interest->id) ?>.html" style="margin-bottom:20px">
                    <div class="pane-cont">
                        <div class="price"><span><?= $interest->sum ?></span> тенге</div>
                        <div style="margin-top:20px;color:#000;"><?= $interest->title ?></div>
                        <div class="hr">&nbsp;</div>
                        <i style="color:#999"><?= $interest->notes ?></i>
                        <div style="color:#000;padding:5px 0">Доставка до: <?= I18n::date($interest->deliver_at) ?></div>
                        <div style="color:#000;padding:0px 0px 5px 0px">Осталось: <b><?= $interest->left ?></b></div>
                    </div>
                </a>
            <? endforeach; ?>
        <? endif; ?>
    </div>
    <div class="item-body">
        <div class="pane">
            <div class="pane-cont">
                <div id="vk_comments"></div>
                <script type="text/javascript">
                    VK.Widgets.Comments("vk_comments", {limit: 10, width: "560", attach: false});
                    VK.Observer.subscribe("widgets.comments.new_comment",function(num,lc){
                        $.get('/<?=$model->name?>-project/comments.html',{'update':num,'token':'<?=X3::user()->token?>'})
                    });
                    VK.Observer.subscribe("widgets.comments.delete_comment",function(num,lc){
                        $.get('/<?=$model->name?>-project/comments.html',{'update':num,'token':'<?=X3::user()->token?>'})
                    });
                </script>
            </div>
        </div>
    </div>
    <div class="clear" style="height: 120px">&nbps;</div>
</div>
<script type="text/javascript" src="//yandex.st/share/share.js"
charset="utf-8"></script>