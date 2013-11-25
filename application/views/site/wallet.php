<?php
$form = new Form();
$user = User::getByPk(X3::user()->id);
$per = (float)strip_tags(SysSettings::getValue('WalletComittion','text','Комиссия с личного кошелька','Общие','0%'));
$actual = $invest->amount + $invest->amount * $per/100;
if($user->money >= $actual) {
    $wallet_text = SysSettings::getValue('WalletText','text','Текст для оплаты с личного счета','Общие','<p>Подтвердите, что хотите вложить %AMOUNT% тенге в этот проект</p>');
    $wallet_text = str_replace("%AMOUNT%",$invest->amount,$wallet_text);
    $button_text = "Подтверждаю";
} else {
    $wallet_text = SysSettings::getValue('WalletTextInsufitionFunds','text','Текст об отсутствии суммы для вложения с личного счета','Общие','<p>У вас не хватает %AMOUNT% тенге чтобы оплатить %SUM% тенге с учетом коммисии</p>');
    $wallet_text = str_replace("%AMOUNT%",$actual - $user->money, $wallet_text);
    $wallet_text = str_replace("%SUM%",$actual, $wallet_text);
    $button_text = "Перейти на страницу проекта";
}
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
        <?=$wallet_text?>
        <div class="hr">&nbsp;</div>
        <?if($per>0):?>
        <p>Комиссия с личного счета: <?=$per?>%</p>
        <p>Всего к оплате: <?=number_format($actual,2,',',' ')?>тг.</p>
        <div class="hr">&nbsp;</div>
        <?endif;?>
        <?=$form->start(array('id'=>'wallet'))?>
            <button type="submit" id="pay" name="wallet"><?=$button_text?></button>
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