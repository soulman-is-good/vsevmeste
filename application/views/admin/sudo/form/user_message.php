<?php
$title = $model->moduleTitle();
$pk = $model->getTable()->getPK();
$errors = $model->getTable()->getErrors();
$fields = array('title','text');
$form = new Form($model);
if(isset($_GET['toid']))
    $model->to_user_id = (int)$_GET['toid'];
?>
<div class="eksk-wnd login">
    <div class="head"><h1 class="center">Написать сообщение <?=$model->to_user_id()->fullName?></h1></div>
    <div class="content">
        <?if(!empty($errors)):?>
        <div class="errors">
            <ul>
                <?foreach($errors as $errs):?>
                    <?foreach($errs as $error):?>
                <li><?=$error?></li>
                    <?endforeach;?>
                <?endforeach;?>
            </ul>
        </div>
        <?endif;?>
        <?=$form->start()?>
        <?if(!$model->getTable()->getIsNewRecord()):?>
        <?=$form->hidden($pk)?>
        <?endif;?>
        <?if($model->to_user_id>0):?>
        <?=$form->hidden('to_user_id',array('value'=>$model->to_user_id))?>
        <?else:?>
        <?=$form->select('to_user_id')?>
        <?endif;?>
        <?=$form->hidden('from_user_id',array('value'=>X3::user()->id))?>
        <table class="eksk-form login-form">
        <?
        echo $form->renderPartial($fields);
        //echo $form->renderPartial(array('email'=>X3::translate('Ваш E-mail или мобильный телефон'),'password'=>X3::translate('Пароль')));
        ?>
            <tr><td align="center" colspan="3"><div class="wrapper inline-block"><button type="submit">Сохранить</button></div></td></tr>
        </table>
        <?=$form->end()?>
    </div>
    <div class="shadow"><i></i><b></b><em></em></div>
</div>
