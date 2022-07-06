<?php
require_once dirname(__DIR__) . '/config/init.php'; //подключаем константы и используем параменры
require_once LIBS . '/functions.php';   //подключаем функции
require_once CONF . '/routes.php';   //подключаем функции

new \ishop\App();



