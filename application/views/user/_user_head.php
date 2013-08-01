<div class="item-head">
    <div class="item-head-body">
        <img class="logo" src="<?=$user->getAvatar('220x220')?>" alt="" />
        <div class="item-desc">
            <h1 style="margin-bottom: 15px;display: inline-block"><?=X3_Html::encode($user->fullname)?></h1>
            <?if(X3::user()->id == $user->id):?>
            <a href="/user/edit.html" style="font-size: 16px;font-weight: bold;border-bottom: 1px solid #ADD299;margin:5px 0 0 10px;display: inline-block">Редактировать профиль</a>
            <?endif;?>
            <br/>
            <?/*<p style="margin-bottom: 15px">Счет на сайте: <strong>0 тенге</strong></p>*/?>
            <?/*if(X3::user()->id == $user->id):?>
            <a href="#" style="font-size: 16px;font-weight: bold;border-bottom: 1px solid #ADD299">Пополнить</a>
            <?endif;*/?>
        </div>
        <div class="clear">&nbsp;</div>
    </div>
</div>