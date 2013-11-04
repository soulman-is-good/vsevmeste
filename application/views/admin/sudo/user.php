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
    </div>
    <div class="content">
        <div class="admin-list">
            <?foreach($models as $_model):
                $model = new User();
                $model->getTable()->acquire($_model);
                $model->id = $_model['id'];
                $model->getTable()->setIsNewRecord(false);
                ?>
                <div class="message_block">
                    <div class="inside_block">
                        <div class="right_side" style="float:right;width:250px;text-align: right">
                            <div class="wrapper">
                                <div class="btn-group">
                                    <a class="btn btn-primary" href="/admin/edit/module/<?=$class?>/id/<?=$model->id?>.html"><i class="icon-user icon-white"></i> Редактировать</a>
                                    <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="/admin/edit/module/<?=$class?>/id/<?=$model->id?>.html" class="btn">Редактировать</a></li>
                                        <li><a href="/admin/delete/module/<?=$class?>/id/<?=$model->id?>.html" class="btn btn-danger" onclick="return confirm('Вы уверены?');">Удалить</a></li>
                                        <li class="divider"></li>
                                        <?if($model->status==0):?>
                                        <li><a href="/admin/update/module/<?=$class?>/id/<?=$model->id?>.html?field=status&value=1">Активировать</a></li>
                                        <?else:?>
                                        <li><a href="/admin/update/module/<?=$class?>/id/<?=$model->id?>.html?field=status&value=0">Деактивировать</a></li>
                                        <?endif;?>
                                        <?if(!$model->ispartner):?>
                                        <li><a href="/admin/update/module/<?=$class?>/id/<?=$model->id?>.html?field=ispartner&value=1">Это партнер</a></li>
                                        <?else:?>
                                        <li><a href="/admin/update/module/<?=$class?>/id/<?=$model->id?>.html?field=ispartner&value=0">Убрать статус партнера</a></li>
                                        <?endif;?>
                                        <?if($model->role === 'admin'):?>
                                        <li><a href="/admin/update/module/<?=$class?>/id/<?=$model->id?>.html?field=role&value=user" >Убрать админ права</a></li>
                                        <?else:?>
                                        <li><a href="/admin/update/module/<?=$class?>/id/<?=$model->id?>.html?field=role&value=admin" >Сделать админом</a></li>
                                        <?endif;?>
                                        <li><a href="/admin/list/module/User_Message/toid/<?=$model->id?>.html" >Сообщения</a></li>
                                        <li><a style="color: #009900" href="#" class="money-popup" data-userid="<?=$model->id?>" >Пополнить счет</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="middle_side" style="width:auto"> 
                                <a href="/admin/view/module/<?=$class?>/id/<?=$model->id?>.html" style="float:left;">
                                    <img src="<?=$model->getAvatar();?>" />
                                </a>
                                <a href="/admin/view/module/<?=$class?>/id/<?=$model->id?>.html">
                                    <?=$model->fullName;?><?if($model->status==0):?>&nbsp;<span class="label label-inverse">Не активирован</span><?endif;?><?if($model->ispartner):?>&nbsp;<span class="label label-info">Партнер</span><?endif;?>
                                    <?if($model->role === 'admin'):?>
                                    <span class="label label-important">Администратор</span>
                                    <?endif?>
                                </a>
                                <br/>
                                Счет на сайте: <strong><?=number_format($model->money,2,',',' ')?> тенге</strong>
                                <div class="clear">&nbsp;</div>
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