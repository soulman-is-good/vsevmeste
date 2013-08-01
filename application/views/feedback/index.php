<?php
$form = new Form($model);
?>
<div class="body" style="position: relative">
<h1 style="font-size: 30px;margin:25px 0">Написать нам</h1>
<div class="pane" style="margin-bottom:40px;">
<div class="pane-cont">
<div class="reg form">
    <div class="content"  style="padding-top: 15px">
        <?if($model->hasErrors()):?>
        <?=  X3_Html::errorSummary($model)?>
        <?endif;?>
        <?=$form->start()?>
        <table class="eksk-form login-form">
            <tr>
                <td class="field" colspan="2">
                    <?=$form->input('name',array('placeholder'=>'Введите ваше имя','value'=>X3::user()->fullname));?>
                </td>
            </tr>
            <tr>
                <td class="field" colspan="2">
                    <?=$form->input('email',array('placeholder'=>'E-mail','value'=>X3::user()->email));?>
                </td>
            </tr>
            <tr>
                <td class="field" colspan="2">
                    <?=$form->textarea('text',array('placeholder'=>'Ваше сообщение'));?>
                </td>
            </tr>
            <?if(X3::user()->isGuest()):?>
            <tr>
                <td class="field" colspan="2">
                    <input name="captcha" id="captcha" value="" style="width:95px" placeholder="Код с картинки" type="text" />
                    <a href="#update" onclick="$(this).children('img').attr('src','/uploads/captcha.gif?F5='+Math.random()*100);return false;"><img width="160" height="28" src="/uploads/captcha.gif" /></a>
                </td>
            </tr>
            <?endif;?>
            <tr>
                <td align="left" colspan="2"  style="padding-top: 15px">
                    <button type="submit"><?=X3::translate('Отправить');?></button>
                </td>
            </tr>
        </table>
        <?=$form->end()?>
    </div>
</div>
</div>
</div>
</div>
