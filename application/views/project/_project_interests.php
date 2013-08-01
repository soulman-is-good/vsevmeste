<? if ($interests->count() > 0): ?>
    <div class="invest-label">Вложить в проект:</div>
    <? foreach ($interests as $interest): ?>
        <a class="pane" href="/<?= $model->name ?>-project/i<?= sprintf("%010d", $interest->id) ?>.html" style="margin-bottom:20px">
            <div class="pane-cont">
                <div class="price"><span><?= X3_Html::encode($interest->sum) ?></span> тенге</div>
                <div style="margin-top:20px;color:#000;"><?= X3_Html::encode($interest->title) ?></div>
                <div class="hr">&nbsp;</div>
                <i style="color:#999"><?= X3_Html::encode($interest->notes) ?></i>
                <div style="color:#000;padding:5px 0">Доставка до: <?= I18n::date($interest->deliver_at) ?></div>
                <div style="color:#000;padding:0px 0px 5px 0px">Куплено: <b><?= $interest->bought ?>/<?= $interest->limit ?></b></div>
                <div style="color:#000;padding:0px 0px 5px 0px">Осталось: <b><?= $interest->limit - $interest->bought ?></b></div>
            </div>
        </a>
    <? endforeach; ?>
<? endif; ?>