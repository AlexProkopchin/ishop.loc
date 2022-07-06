<?php

namespace app\widgets\menu;

use ishop\App;
use ishop\Cache;

class Menu
{
    protected $data;    //храним данные
    protected $tree;    //массив дерева которое строим из данных
    protected $menuHtml;    //готовый код меню
    protected $tpl;     //шаблон для меню
    protected $container = 'ul';    //контейнер для меню
    protected $class = 'menu';  // класс тега
    protected $table = 'category';   //таблица БД из которой будем выберать
    protected $cache = 3600;    //на какое время кешируем данные
    protected $cacheKey = 'ishop_menu';    //ключ под которым хранится кеш
    protected $attrs = [];  //массив атрибутов
    protected $prepend = '';    //для админки
    

    public function __construct($options = [])
    {
        $this->tpl = __DIR__ . '/menu_tpl/menu.php';
        $this->getOptions($options);
        $this->run();
    }

    protected function getOptions($options)
    {
        foreach($options as $k => $v)
        {
            if(property_exists($this, $k))  //проверяем есть ли такое свойство
            {
                $this->$k = $v;
            }
        }
    }

    protected function run()
    {
        $cache = Cache::instance();
        $this->menuHtml = $cache->get($this->cacheKey);
        if(!$this->menuHtml)
        {
            $this->data = App::$app->getProperty('cats');
            if(!$this->data)
            {
                $this->data = \R::getAssoc("SELECT * FROM {$this->table}");
            }
            $this->tree = $this->getTree();
            $this->menuHtml = $this->getMenuHtml($this->tree);
            if($this->cache)
            {
                $cache->set($this->cacheKey, $this->menuHtml,$this->cache);
            }
        }
        $this->output();
    }

    protected function output()
    {
        $attrs = '';
        if(!empty($this->attrs))
        {
            foreach($this->attrs as $k => $v)
            {
                $attrs .= " $k='$v' ";
            }
        }
        echo "<{$this->container} class='{$this->class}' $attrs >";
            echo $this->prepend;
            echo $this->menuHtml;
        echo "</{$this->container}>";
    }

    // собирает из массива дерево
    protected function getTree()
    {
        $tree = [];
        $data = $this->data;
        foreach($data as $id => &$node)
        {
            if (!$node['parent_id'])
            {
                $tree[$id] = &$node;
            }
            else
            {
                $data[$node['parent_id']]['childs'][$id] = &$node;
            }
        }
        return $tree;
    }

    // получает html код
    protected function getMenuHtml($tree, $tab = '')
    {
        $str = '';
        foreach($tree as $id => $category)
        {
            $str .= $this->catToTemplate($category, $tab, $id);
        }
        return $str;
    }

    //  из категории собираем кусок html кода
    protected function catToTemplate($category, $tab, $id)
    {
        ob_start();
        require $this->tpl;
        return ob_get_clean();
    }
}