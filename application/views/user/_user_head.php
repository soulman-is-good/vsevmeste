<style>
    .b-share-icon {
        float: left;
        display: inline;
        overflow: hidden;
        width: 16px;
        height: 16px;
        padding: 0!important;
        vertical-align: top;
        border: 0;
        background: url(//yandex.st/share/static/b-share-icon.png) 0 99px no-repeat;
        margin-right:5px;
    }
.b-share-icon_vkontakte,.b-share-icon_custom{background-position:0 0}.b-share-icon_yaru,.b-share-icon_yaru_photo,.b-share-icon_yaru_wishlist{background-position:0 -17px}.b-share-icon_lj{background-position:0 -34px}.b-share-icon_twitter{background-position:0 -51px}.b-share-icon_facebook{background-position:0 -68px}.b-share-icon_moimir{background-position:0 -85px}.b-share-icon_friendfeed{background-position:0 -102px}.b-share-icon_mail{background-position:0 -119px}.b-share-icon_html{background-position:0 -136px}.b-share-icon_postcard{background-position:0 -153px}.b-share-icon_odnoklassniki{background-position:0 -170px}.b-share-icon_blogger{background-position:0 -187px}.b-share-icon_greader{background-position:0 -204px}.b-share-icon_delicious{background-position:0 -221px}.b-share-icon_gbuzz{background-position:0 -238px}.b-share-icon_linkedin{background-position:0 -255px}.b-share-icon_myspace{background-position:0 -272px}.b-share-icon_evernote{background-position:0 -289px}.b-share-icon_digg{background-position:0 -306px}.b-share-icon_juick{background-position:0 -324px}.b-share-icon_moikrug{background-position:0 -341px}.b-share-icon_yazakladki{background-position:0 -358px}.b-share-icon_liveinternet{background-position:0 -375px}.b-share-icon_tutby{background-position:0 -392px}.b-share-icon_diary{background-position:0 -409px}.b-share-icon_gplus{background-position:0 -426px}.b-share-icon_pocket{background-position:0 -443px}.b-share-icon_surfingbird{background-position:0 -460px}.b-share-icon_pinterest{background-position:0 -477px}
.b-share-icon_youtube {
    background: url(/js/ckeditor.4/plugins/youtube/images/icon.png) 0 0 no-repeat;
}
</style>
<div class="item-head">
    <div class="item-head-body">
        <img class="logo" src="<?=$user->getAvatar('220x220')?>" alt="" />
        <div class="item-desc">
            <?if($user->about!=''):?>
            <h1 style="margin-bottom: 5px;display: inline-block"><?=X3_Html::encode($user->fullname)?></h1>
                <?if(X3::user()->id == $user->id):?>
                <a href="/user/edit.html" style="font-size: 16px;font-weight: bold;border-bottom: 1px solid #ADD299;margin:5px 0 0 10px;display: inline-block">Редактировать профиль</a>
                <br/>
                <?endif;?>
            <div class="info" style="margin-bottom:10px;font-style: italic;margin-left:4px;padding-left:4px;border-left:1px solid #888;color:#555;font-size: 12px"><?=nl2br($user->about)?></div>
            <?else:?>
            <h1 style="margin-bottom: 15px;display: inline-block"><?=X3_Html::encode($user->fullname)?></h1>
                <?if(X3::user()->id == $user->id):?>
                <a href="/user/edit.html" style="font-size: 16px;font-weight: bold;border-bottom: 1px solid #ADD299;margin:5px 0 0 10px;display: inline-block">Редактировать профиль</a>
                <br/>
                <?endif;?>
            <?endif;?>
            <?if($user->contact_email!=''):?>
            <p style="margin-bottom: 5px">Контактный E-mail: <a href="mailto:<?=$user->contact_email?>"><?=$user->contact_email?></a></p>
            <?endif;?>
            <?if($user->contact_phone!=''):?>
            <p style="margin-bottom: 5px">Контактный телефон: <strong><?=$user->contact_phone?></strong></p>
            <?endif;?>
            <?if(trim($user->links,"\r\n ") != ''):
                if(strpos($user->links,"\n")!==false)
                    $links = explode("\n",$user->links);
                else
                    $links = explode(" ",$user->links);
            ?>
            <p style="margin-bottom: 5px">
                <?foreach($links as $link):if(trim($link)=='') continue;?>
                <a href="<?=X3_Html::encode($link)?>" target="_blank">
                    <?if(strpos($link,'youtu')):?>
                    <span class="b-share-icon b-share-icon_youtube"></span>
                    <?endif;?>
                    <?if(strpos($link,'facebook')):?>
                    <span class="b-share-icon b-share-icon_facebook"></span>
                    <?endif;?>
                    <?if(strpos($link,'twi')):?>
                    <span class="b-share-icon b-share-icon_twitter"></span>
                    <?endif;?>
                    <?if(strpos($link,'moi')):?>
                    <span class="b-share-icon b-share-icon_moimir"></span>
                    <?endif;?>
                    <?if(strpos($link,'vk')):?>
                    <span class="b-share-icon b-share-icon_vkontakte"></span>
                    <?endif;?>
                    <?if(strpos($link,'google')):?>
                    <span class="b-share-icon b-share-icon_gplus"></span>
                    <?endif;?>
                    <?if(strpos($link,'linked')):?>
                    <span class="b-share-icon b-share-icon_linkedin"></span>
                    <?endif;?>
                </a>
                <?endforeach;?>
                <br/>
            </p>
            <?endif;?>
            <?if(X3::user()->id == $user->id):?>
            <p style="margin-bottom: 15px">Счет на сайте: <strong><?=number_format($user->money,2,',',' ')?> тенге</strong></p>
            <a href="/update-account.phtml" style="font-size: 16px;font-weight: bold;border-bottom: 1px solid #ADD299">Пополнить</a>
            <?endif;?>
        </div>
        <div class="clear">&nbsp;</div>
    </div>
</div>