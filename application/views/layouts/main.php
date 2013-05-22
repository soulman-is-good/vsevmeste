<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?if(X3::app()->nofollow):?>
<meta name="robots" content="noindex, nofollow" />
<?endif;?>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="google-site-verification" content="vYMUNjNbQeieKvgw7Z6EPAP0GHXkdrVJub6nEGMTu1A" />
<meta property="og:image" content="<?=X3::app()->baseUrl?>/images/logo.png" />
<link href="/css/style.css" type="text/css" rel="stylesheet" />
<link href="/css/font/junegull.css" rel="stylesheet" type="text/css" />
<title><?=X3::app()->name?></title>
<link href="/js/tipTip.css" type="text/css" rel="stylesheet" />
<link href="/favicon.ico" rel="shortcut icon" type="image/x-icon">
</head>
<body itemscope itemtype="http://schema.org/WebPage" class="<?=X3::app()->module->controller->id." ".X3::app()->module->controller->action?><?=X3::user()->isGuest()?' unauthorized':''?>">
<!--
Development and Layout - Maxim Savin <http://careers.stackoverflow.com/soulman>
Based on x3framework2.0
working with Zuber.kz
-->
        <?=X3_Widget::run('@layouts:header.php',array('main'=>isset($main)))?>
        <?=$content?>
        <?=X3_Widget::run('@layouts:footer.php',array('main'=>isset($main)));?>
<script type="text/javascript">
String.prototype.repeat = function( num ){return new Array( num + 1 ).join( this );}    
</script>
<script type="text/javascript" src="/js/jquery.js"></script>
<?if(isset(X3::app()->datapicker)):?>
<link href="http://code.jquery.com/ui/1.10.0/themes/cupertino/jquery-ui.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
<?endif;?>
<script type="text/javascript" src="/js/jquery.tipTip.js"></script>
<script type="text/javascript" src="/js/jquery.fcselect.js"></script>
<script type="text/javascript" src="/js/jquery.fctabs.js"></script>
<script type="text/javascript" src="/js/jquery.maskedinput.min.js"></script>
<script type="text/javascript" src="/js/placeholder.js"></script>
<script type="text/javascript" src="/js/wnd.js"></script>
<script type="text/javascript" src="http://api-maps.yandex.ru/2.0-stable/?lang=ru-RU&coordorder=longlat&load=package.full"></script>

<?if(X3::user()->superAdmin):?>
<script type="text/javascript" src="/js/admin.js"></script>
    <script type="text/javascript" src="/js/ckeditor.4/ckeditor.js"></script>
    <?include("js/sfbrowser/connectors/php/init.php");?>
<?endif;?>

<script type="text/javascript" src="/js/script.js"></script>
</body>
</html>