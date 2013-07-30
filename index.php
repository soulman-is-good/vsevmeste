<?php
header('Content-type: text/html; charset=utf-8');
if (get_magic_quotes_gpc()) {
    function strip_array($var) {
        return is_array($var)? array_map("strip_array", $var):stripslashes($var);
    }

    $_POST = strip_array($_POST);
    $_SESSION = strip_array($_SESSION);
    $_GET = strip_array($_GET);
}
// change the following paths if necessary
$x3=dirname(__FILE__).'/application/x3framework/X3.php';
$config=dirname(__FILE__).'/application/config/main.php';
// remove the following lines when in production mode
define('X3_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
ini_set('display_errors', true);
error_reporting(E_ALL);
require_once($x3);
$app = X3::init($config);
$app->run();
?>
