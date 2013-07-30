<?php
$form = new Form('User');
?>
<div class="body" style="position: relative">
<h1 style="font-size: 30px;margin:25px 0">Восстановление пароля</h1>
<div class="pane" style="margin-bottom:40px;">
<div class="pane-cont">
<div class="auth form">
    <h3>Введите новый пароль</h3>
    <div class="content"  style="padding-top: 15px">
        <?if($error!=''):?>
        <div class="errors">
            <ul>
                <li><?=$error?></li>
            </ul>
        </div>
        <?endif;?>
        <?=$form->start()?>
        <table class="eksk-form login-form">
            <tr>
                <td class="field" colspan="2">
                    <?=X3_Html::form_tag('input',array('placeholder'=>'Новый пароль','type'=>'password','name'=>'password'));?>
                </td>
            </tr>
            <tr>
                <td class="field" colspan="2">
                    <?=X3_Html::form_tag('input',array('placeholder'=>'Повторите пароль','type'=>'password','name'=>'password_repeat'));?>
                </td>
            </tr>
            <tr>
                <td align="left"  colspan="2" style="padding-top: 15px">
                    <button type="submit"><?=X3::translate('Восстановить');?></button>
                </td>
            </tr>
        </table>
        <?=$form->end()?>
    </div>
</div>
</div>
</div>
</div>
