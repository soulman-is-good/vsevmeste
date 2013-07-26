<?php
$form = new Form($user);
$errors = $user->getTable()->getErrors();
?>
<div class="body" style="position: relative">
<h1 style="font-size: 30px;margin:25px 0">Зарегистрироваться или авторизироваться</h1>
<div class="pane" style="margin-bottom:40px;">
<div class="pane-cont">
<div class="auth form" style="float:left;margin-right: 40px;">
    <h3>Авторизация</h3>
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
                    <?=$form->input('email',array('placeholder'=>'E-mail'));?>
                </td>
            </tr>
            <tr>
                <td class="field" colspan="2">
                    <?=$form->input('password',array('placeholder'=>'Пароль','type'=>'password'));?>
                </td>
            </tr>
            <tr>
                <td class="field">
                    <input type="checkbox" name="rememberme" id="rememberme" /> <label for="rememberme" style="font-size:12px;position: relative;top:-2px">Запомнить меня</label>
                </td>
                <td class="field" align="right">
                    <a style="text-decoration: underline;font-size:12px" href="/forgot-my-password.html">Забыли пароль?</a>
                </td>
            </tr>
            <tr>
                <td align="left"  colspan="2" style="padding-top: 15px">
                    <button type="submit"><?=X3::translate('Войти');?></button>
                </td>
            </tr>
        </table>
        <?=$form->end()?>
    </div>
</div>
<div class="reg form">
    <h3><?=X3::translate('Регистрация');?></h3>
    <div class="content"  style="padding-top: 15px">
        <?if(!empty($errors)):?>
        <div class="errors">
            <ul>
                <?foreach($errors as $errs):?>
                <?foreach($errs as $err):?>
                <li><?=$err?></li>
                <?endforeach;?>
                <?endforeach;?>
            </ul>
        </div>
        <?endif;?>
        <?=$form->start()?>
        <table class="eksk-form login-form">
<?/*            <tr>
                <td class="field" colspan="2">
                    <?=$form->input('name',array('placeholder'=>'Имя'));?>
                </td>
            </tr>
            <tr>
                <td class="field" colspan="2">
                    <?=$form->input('surname',array('placeholder'=>'Фамилия'));?>
                </td>
            </tr>*/?>
            <tr>
                <td class="field" colspan="2">
                    <?=$form->input('email',array('placeholder'=>'E-mail'));?>
                </td>
            </tr>
            <tr>
                <td class="field" colspan="2">
                    <?=$form->input('password',array('placeholder'=>'Пароль','type'=>'password'));?>
                </td>
            </tr>
            <tr>
                <td class="field" colspan="2">
                    <?=$form->input('password_repeat',array('placeholder'=>'Повторите пароль','type'=>'password'));?>
                </td>
            </tr>
            <tr>
                <td class="field" colspan="2">
                    <input name="captcha" id="captcha" value="" style="width:95px" placeholder="Код с картинки" type="text" />
                    <a href="#update" onclick="$(this).children('img').attr('src','/uploads/captcha.gif?F5='+Math.random()*100);return false;"><img width="160" height="28" src="/uploads/captcha.gif" /></a>
                </td>
            </tr>
<?/*            <tr>
                <td class="label">
                </td>
                <td class="field">
                    <?=$form->checkbox('iagree')?>&nbsp;<label for="User_iagree"><?=$user->fieldName('iagree')?></label>
                </td>
                <td class="error">
                    <?//$form->error('region_id')?>
                </td>
            </tr>*/?>
            <tr>
                <td align="left" colspan="2"  style="padding-top: 15px">
                    <button type="submit"><?=X3::translate('Зарегистрироваться');?></button>
                </td>
            </tr>
        </table>
        <?=$form->end()?>
    </div>
</div>
</div>
</div>
</div>
