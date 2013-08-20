<?php $smini = SysSettings::getModel('ProjectAddRulesMini', 'text', 'Требования'); ?>
<div class="right-bar item-show-bar" style="width:280px">
    <div class="pane">
        <div class="pane-cont">
            <h3><?= $smini->title ?></h3>
            <div class="hr">&nbsp;</div>
            <div>
                <?= $smini->value ?>
            </div>
        </div>
    </div>
</div>