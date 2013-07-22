<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="robots" content="noindex, nofollow" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link href="/css/bootstrap.min.css?<?=  filemtime('css/bootstrap.min.css')?>" type="text/css" rel="stylesheet" />
<link href="/css/bootstrap-responsive.min.css" type="text/css" rel="stylesheet" />
<link href="/css/style.css?<?=  filemtime('css/style.css')?>" type="text/css" rel="stylesheet" />
<link href="/css/admin.css?<?=  filemtime('css/admin.css')?>" type="text/css" rel="stylesheet" />
<link href="/css/font/junegull.css" rel="stylesheet" type="text/css" />
<title><?=X3::app()->name?></title>
<link href="/js/tipTip.css" type="text/css" rel="stylesheet" />
<link href="/favicon.ico" rel="shortcut icon" type="image/x-icon">
</head>
<body itemscope itemtype="http://schema.org/WebPage" class="admin-part">
<!--
Development and Layout - Maxim Savin <http://careers.stackoverflow.com/soulman>
Based on x3framework2.0
working with Zuber.kz
-->
<div class="wrap">
        <?=X3_Widget::run('@layouts:admin_header.php')?>
        <?=X3_Widget::run('@layouts:admin_menu.php')?>
        <div class="body" style="margin: 20px auto; padding:0">
            <?=$content?>
        </div>
        <?=X3_Widget::run('@layouts:admin_footer.php');?>
</div>
<script type="text/javascript">
String.prototype.repeat = function( num ){return new Array( num + 1 ).join( this );}    
</script>
<script type="text/javascript" src="/js/jquery.js"></script>
<link href="http://code.jquery.com/ui/1.10.0/themes/cupertino/jquery-ui.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
<script type="text/javascript" src="/js/jquery.tipTip.js"></script>
<script type="text/javascript" src="/js/jquery.fcselect.js"></script>
<script type="text/javascript" src="/js/jquery.fctabs.js"></script>
<script type="text/javascript" src="/js/jquery.maskedinput.min.js"></script>
<script type="text/javascript" src="/js/placeholder.js?<?=  filemtime('js/placeholder.js')?>"></script>
<script type="text/javascript" src="/js/wnd.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="http://api-maps.yandex.ru/2.0-stable/?lang=ru-RU&coordorder=longlat&load=package.full"></script>

<script type="text/javascript" src="/js/admin.js"></script>
    <script type="text/javascript" src="/js/ckeditor.4/ckeditor.js"></script>
    <?include("js/sfbrowser/connectors/php/init.php");?>

<script type="text/javascript" src="/js/script.js?<?=  filemtime('js/script.js')?>"></script>
</body>
</html>