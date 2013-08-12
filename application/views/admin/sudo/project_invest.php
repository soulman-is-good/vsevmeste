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
    </div>
    <div class="content">
        <div class="admin-list">
            <?foreach($models as $model):
                $M = new Project_Invest;
                $M->getTable()->acquire($model);
                ?>
                <div class="message_block">
                    <div class="inside_block">
                        <div class="right_side" style="float:right;width:250px;text-align: right">
                            <div class="wrapper">
                                <a href="/admin/edit/module/<?=$class?>/id/<?=$model[$pk]?>.html" class="btn btn-mini">Редактировать</a><br/>
                                <a href="/admin/delete/module/<?=$class?>/id/<?=$model[$pk]?>.html" class="btn btn-mini btn-danger" onclick="return confirm('Вы уверены?');">Удалить</a>
                            </div>
                        </div>
                        <div class="middle_side" style="width:auto"> 
                                <a href="/admin/view/module/<?=$class?>/id/<?=$model[$pk]?>.html">
                                    #<?=$model[$pk]?>
                                </a>
                                <a href="/admin/view/module/User/id/<?=$M->user_id?>.html"><?=$M->user_id()->fullName?></a> вложил в <b><?=$M->project_id()->title?></b>
                                <br/><?=$model['amount']?> тенге
                                <br/><?=I18n::date($M->created_at)?> в <?=date("H:i:s",$M->created_at)?> 
                                    <?if($M->status==0):?><span class="label label-important">отменен пользователем</span><?endif;?>
                                    <?if($M->status==2):?><span class="label label-important">в ожидании или отменен</span><?endif;?>
                                    <?if($M->status==5):?><span class="label label-important">ошибка обработки</span><?endif;?>
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
