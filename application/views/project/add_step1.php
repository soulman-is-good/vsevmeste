<?php
$form = new Form;
$s = SysSettings::getModel('ProjectAddRules', 'text', 'Правила при размещении проекта');
?>
<div class="item-head">
    <div class="item-head-body">
        <h1 style="font-size:30px;margin:0 0 0 30px"><?=$s->title?></h1>
    </div>
</div>
<div class="body project-add" style="position: relative">
    <?php $smini = SysSettings::getModel('ProjectAddRulesMini', 'text', 'Требования');?>
    <div class="right-bar item-show-bar" style="width:280px">
        <div class="pane">
            <div class="pane-cont">
                <h3><?=$smini->title?></h3>
                <div class="hr">&nbsp;</div>
                <div>
                    <?=$smini->value?>
                </div>
            </div>
        </div>
    </div>
    <div class="tabs" style="top:-79px">
        <ul>
            <li class="active"><em>1</em> - Правила</li>
            <li><a href="#" onclick="return false;"><em>2</em> - Проект</a></li>
            <li><a href="#" onclick="return false;"><em>3</em> - Необходимая сумма</a></li>
            <?if(!$user->filled):?>
            <li><a href="#" onclick="return false;"><em>4</em> - Личная информация</a></li>
            <?endif;?>
        </ul>
    </div>
    <div class="pane" style="margin:30px 0;width:615px">
        <div class="pane-cont">
            <div class="form">
                <?=$form->start();?>
                <div class="field">
                    <?=$s->value?>
                </div>
                <div class="field">
                    <button name="agree" type="submit">Я согласен с правилами</button>
                </div>
                <?=$form->end();?>
            </div>
        </div>
    </div>
</div>