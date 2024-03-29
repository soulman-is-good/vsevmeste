<?php
//$qiwicode = sprintf("%011d",$invest->id);
$qiwicode = $invest->id;
$form = new Form();
$per = (float)strip_tags(SysSettings::getValue('QiwiComittion','string','Комиссия Qiwi','Общие','1%'));
$qiwi_text = SysSettings::getValue('QiwiText','text','Текст для оплаты через терминалы Qiwi','Общие','<p>Чтобы вложить %AMOUNT% тенге в этот проект, необходимо произвести оплату в любом терминале Qiwi<br/>Выберите способ оплаты "vsevmeste.kz" и введите номер счета <b>%CODE%</b></p>');
$qiwi_text = str_replace("%AMOUNT%",$invest->amount,$qiwi_text);
$qiwi_text = str_replace("%CODE%",$qiwicode,$qiwi_text);
?>
<div class="body" style="position: relative">
<h1 style="font-size: 30px;margin:25px 0">Вложение денежных средств в проект "<?=$invest->project_id()->title?>"</h1>
<div class="pane" style="margin-bottom:40px;">
<div class="pane-cont">
<div class="auth form">
    <div class="content"  style="padding-top: 15px">
        <?if($error!=''):?>
        <div class="errors">
            <ul>
                <li><?=$error?></li>
            </ul>
        </div>
        <?endif;?>
        <?=$qiwi_text?>
        <div class="hr">&nbsp;</div>
        <?if($per>0):?>
        <p>Комиссия Qiwi: <?=$per?>%</p>
        <p>Всего к оплате: <?=number_format($invest->amount + $invest->amount * $per/100,2,',',' ')?>тг.</p>
        <div class="hr">&nbsp;</div>
        <?endif;?>
        
        <?=$form->start(array('id'=>'wallet'))?>
		<button type="submit" id="pay" name="qiwi">Вернуться на страницу проекта</button>
        <?=$form->end()?>
    </div>
</div>
</div>
</div>
</div>
<iframe id="frame" name="frame" style="border:none;width:0;height:0;position: absolute;left:-9999px;visibility: hidden">
</iframe>
<?php
//X3::clientScript()->registerScript('button', '$("#pay").remove();$("#epay").submit();');
?>