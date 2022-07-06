<?php

define("DEBUG", 1); //для отладки 1 показываем ошибки, 0 скрываем
define("ROOT", dirname(__DIR__));   //корень нашей папки ishop
define("WWW", ROOT . '/public');
define("APP", ROOT . '/app');
define("CORE", ROOT . '/vendor/ishop/core');
define("LIBS", ROOT . '/vendor/ishop/core/libs');
define("CACHE", ROOT . '/tmp/cache');
define("CONF", ROOT . '/config');
define("LAYOUT", 'watches');


//выбираем протокол HTTP или HTTPS
if (isset($_SERVER['HTTPS']) &&
    ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
    $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
  $protocol = 'https://';
}
else {
  $protocol = 'http://';
}
$app_path = "{$protocol}{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}";
$app_path = preg_replace("#[^/]+$#",'',$app_path);
$app_path = str_replace('/public/','',$app_path); // url главной страницы
define("PATH",$app_path);
define("ADMIN", PATH . '/admin');

require_once ROOT . '/vendor/autoload.php'; // автозагрузчик composer
require_once ROOT . '/vendor/ishop/core/libs/rb.php'; //RedBeanPHP

?>