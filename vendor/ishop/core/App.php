<?php
namespace ishop;
class App{

    public static $app;

    public function __construct()
    {
        $query = trim($_SERVER['QUERY_STRING'], '/');   // получаем строку запроса и обезаем "/"
        session_start();    //начало сессии
        self::$app = Registry::instance();  //переменная класса это новый объект класса Registry
        $this->getParams();
        new ErrorHandler();
        Router::dispatch($query);
    }

    //метод получения параметров из файла params.php
    protected function getParams()
    {
        $params = require_once CONF . '/params.php';
        if(!empty($params))
        {
            foreach($params as $k => $v)
            {
                self::$app->setProperty($k,$v);
            }
        }
    }
}
?>