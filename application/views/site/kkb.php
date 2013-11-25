<?php
$form = new Form();
$per = (float)strip_tags(SysSettings::getValue('EpayComittion','text','Комиссия Epay','Общие','3.5%'));
$kkb_text = SysSettings::getValue('KKBText','text','Текст для оплаты через EPay','Общие','<p>Подтвердите, что хотите вложить %AMOUNT% тенге в этот проект</p>');
$kkb_text = str_replace("%AMOUNT%",$invest->amount,$kkb_text);
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
        <?=$kkb_text?>
        <div class="hr">&nbsp;</div>
        <?if($per>0):?>
        <p>Комиссия по банковской карте: <?=$per?>%</p>
        <p>Всего к оплате: <?=number_format($invest->amount + $invest->amount * $per/100,2,',',' ')?>тг.</p>
        <div class="hr">&nbsp;</div>
        <?endif;?>
        <?=$form->start(array('action'=>'https://3dsecure.kkb.kz/jsp/process/logon.jsp','name'=>'SendOrder','id'=>'epay','enctype'=>null))?>
		<input type="hidden" name="Signed_Order_B64" value="<?= $sign ?>">
		<input type="hidden" name="Language" value="rus" />
                <input type="hidden" name="BackLink" value="<?=X3::request()->getBaseUrl()?>/<?=$invest->project_id()->name?>-project/investments.html" />
                <input type="hidden" name="PostLink" value="<?=X3::request()->getBaseUrl()?>/epay/<?=$invest->id?>" />
		<input type="hidden" name="email" value="<?=$invest->user_id()->email?>" />
		<button type="submit" id="pay">Подтверждаю</button>
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