<?php

namespace app\controllers;

use ishop\Cache;

class MainController extends AppController
{
    public function indexAction(){
        $brands = \R::find('brand','LIMIT 3');  // получаем из БД брендовый товар
        $hits = \R::find('product', "hit = '1' AND status = '1' LIMIT 8"); // получаем хиты (hit=1) из таблицы product, которые разрешены к показу (status=1)
        $this->setMeta('Главная страница', 'Описание', 'Ключевики');
        $this->set(compact('brands', 'hits')); //передаем переменные в вид
    }
}
