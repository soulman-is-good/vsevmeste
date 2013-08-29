<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?if(X3::app()->nofollow):?>
<meta name="robots" content="noindex, nofollow" />
<?endif;?>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="google-site-verification" content="vYMUNjNbQeieKvgw7Z6EPAP0GHXkdrVJub6nEGMTu1A" />
<meta property="og:title" content="<?=X3::app()->og_title?>" />
<meta property="og:url" content="<?=X3::app()->og_url?>" />
<meta property="og:image" content="<?=X3::app()->og_image?>" />
<link href="/css/style.css?<?=  filemtime('css/style.css')?>" type="text/css" rel="stylesheet" />
<link href="/css/font/junegull.css" rel="stylesheet" type="text/css" />
<title><?=X3::app()->name?></title>
<link href="/js/tipTip.css" type="text/css" rel="stylesheet" />
<link href="/favicon.ico" rel="shortcut icon" type="image/x-icon">
<script type="text/javascript">function getCookie(name) {var matches = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"));return matches ? decodeURIComponent(matches[1]) : false;}function setCookie(name, value, options) {options = options || {};var expires = options.expires;if (typeof expires == "number" && expires) {var d = new Date();d.setTime(d.getTime() + expires*1000);expires = options.expires = d;}if (expires && expires.toUTCString){options.expires = expires.toUTCString();}value = encodeURIComponent(value);var updatedCookie = name + "=" + value;for(var propName in options) {updatedCookie += "; " + propName;var propValue = options[propName];if (propValue !== true){updatedCookie += "=" + propValue;}}document.cookie = updatedCookie;}</script>
</head>
<body itemscope itemtype="http://schema.org/WebPage" class="<?=$main && X3::app()->promo?'':'noslider'?> <?=X3::app()->module->controller->id." ".X3::app()->module->controller->action?><?=X3::user()->isGuest()?' unauthorized':''?>">
<!--
Development and Layout - Maxim Savin <http://careers.stackoverflow.com/soulman>
Based on x3framework2.0
working with Zuber.kz
-->
<?if(!isset($_COOKIE['super-strip'])):?>
<div class="super-strip">
    <a class="super-strip-flag" href="#" onclick="setCookie('super-strip','1');$(this).parent().slideUp();return false;"><img src="/images/delete_dis.png" /></a>
    <?=  strip_tags(SysSettings::getValue('HeaderStrip','text[128]','Лента в шапке','Общие','Что такое vsevmeste.kz? Краудфандинговая платформа, принимающая финансирование для авторов проектов. <a href="/about-us.phtml">Подробнее</a>'),'<a>')?>
</div>
<?endif;?>
        <?=X3_Widget::run('@layouts:header.php',array('main'=>isset($main)))?>
<div class="wrap" style="position: relative;height: 100%;">
        <?=$content?>
    <div class="clear">&nbsp;</div>
        <?=X3_Widget::run('@layouts:footer.php',array('main'=>isset($main)),array('cache'=>false));?>
</div>
<script type="text/javascript">
String.prototype.repeat = function( num ){return new Array( num + 1 ).join( this );}    
</script>
<script type="text/javascript" src="/js/jquery.js"></script>
<?if(isset(X3::app()->datapicker)):?>
<link href="http://code.jquery.com/ui/1.10.0/themes/cupertino/jquery-ui.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
<?endif;?>
<?/*<script type="text/javascript" src="/js/jquery.blinds-0.9.js?<?=filemtime('js/jquery.blinds-0.9.js')?>"></script>*/?>
<script type="text/javascript" src="/js/easySlider1.7.js?<?=filemtime('js/easySlider1.7.js')?>"></script>
<script type="text/javascript" src="/js/jquery.tipTip.js"></script>
<script type="text/javascript" src="/js/jquery.fcselect.js"></script>
<script type="text/javascript" src="/js/jquery.fctabs.js"></script>
<script type="text/javascript" src="/js/jquery.maskedinput.min.js"></script>
<script type="text/javascript" src="/js/wnd.js"></script>
<script type="text/javascript" src="http://api-maps.yandex.ru/2.0-stable/?lang=ru-RU&coordorder=longlat&load=package.full"></script>

<?if(X3::user()->superAdmin):?>
<script type="text/javascript" src="/js/admin.js"></script>
    <script type="text/javascript" src="/js/ckeditor.4/ckeditor.js"></script>
    <?include("js/sfbrowser/connectors/php/init.php");?>
<?endif;?>

<script type="text/javascript" src="/js/script.js?<?=  filemtime('js/script.js')?>"></script>
<script type="text/javascript" src="/js/placeholder.js?<?=  filemtime('js/placeholder.js')?>"></script>
</body>
</html>
