<?php
$title = $module->moduleTitle();
$pk = $module->getTable()->getPK();
?>
<div class="eksk-wnd">
    <div class="head">
        <div class="buttons" style="margin: 10px 0;text-align:right">
            <a class="btn btn-large btn-success" id="send_message" href="/admin/create/module/<?=$class?>.html">Добавить</a>
        </div>
        <h1><?=$title?></h1>
        <div class="clear">&nbsp;</div>
        <?/*<div style="position: relative;z-index: 10">
            Дата создания: от <input id="cr_f" type="text" /> до <input id="cr_t" type="text" />
        </div>*/?>
    </div>
    <div class="content">
        <div class="admin-list">
            <?foreach($models as $model):
                $M = new Project;
                $M->getTable()->acquire($model);
                ?>
                <div class="message_block">
                    <div class="inside_block">
                        <div class="right_side" style="float:right;width:250px;text-align: right">
                            <div class="wrapper">
                                <?if($model['status']==0):?>
                                <a href="/admin/update/module/<?=$class?>/id/<?=$model[$pk]?>.html?field=status&value=1" class="btn btn-mini btn-warning">Активироавть</a><br/>
                                <?endif;?>
                                <a href="/admin/list/module/<?=$class?>_Invest.html?filter=<?=  base64_encode("project_id={$model[$pk]}")?>" class="btn btn-mini btn-inverse">Вложения</a><br/>
                                <a href="/admin/edit/module/<?=$class?>/id/<?=$model[$pk]?>.html" class="btn btn-mini">Редактировать</a><br/>
                                <a href="/admin/delete/module/<?=$class?>/id/<?=$model[$pk]?>.html" class="btn btn-mini btn-danger" onclick="return confirm('Вы уверены?');">Удалить</a>
                            </div>
                        </div>
                        <div class="middle_side" style="width:auto"> 
                                <a href="/admin/view/module/<?=$class?>/id/<?=$model[$pk]?>.html">
                                <?=$model['title']?>
                                </a>
                            <?if($model['status']==0):?>
                            <span class="label label-important">Для рассмотрения</span>
                            <?endif;?>
                            <br/>
                            Вложено: <?=$M->current_sum?>/<?=$M->needed_sum?> (<?=$M->getPercentReal()?>%)<br/>
                            Осталось: <?=$M->getTimeLeft()?>
                        </div>
                        <div class="clear">&nbsp;</div>
                    </div>
                </div>
            <?endforeach;?>
        </div>
    </div>
    <div id="navi">
            <?=$paginator?>
    </div>
    <div class="shadow"><i></i><b></b><em></em></div>
</div>