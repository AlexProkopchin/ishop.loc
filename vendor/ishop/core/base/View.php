<?php 

namespace ishop\base;

class View
{
    public $route;
    public $controller;
    public $view;
    public $model;
    public $layout;
    public $prefix;
    public $data = [];
    public $meta = [];

    public function __construct($route, $layout = '', $view = '', $meta)
    {
        $this->route = $route;
        $this->controller = $route['controller'];
        $this->model = $route['controller'];
        $this->view = $view;
        $this->prefix = $route['prefix'];
        $this->meta = $meta;
        if($layout === false)
        {
            $this->layout = false;
        }
        else
        {
            $this->layout = $layout ?: LAYOUT;  //если передана пустая строка то используем шаблон стандартный иначе берем значение
        }
    }

    //формирование страницы
    public function render($data)
    {
        if(is_array($data)) extract($data);
        $viewFile = APP . "/views/{$this->prefix}{$this->controller}/{$this->view}.php";
        if(is_file($viewFile))
        {
            ob_start(); // включаем буферизацию вывода, если буферизация вывода активна, никакой вывод скрипта не отправляется (кроме заголовков), а сохраняется во внутреннем буфере.
            require_once $viewFile;
            $content = ob_get_clean(); // все из буфера положим в переменную
        }
        else
        {
            throw new \Exception("Не найден вид {$viewFile}", 500);
        }
        if($this->layout !== false)
        {
            $layoutFile = APP . "/views/layouts/{$this->layout}.php";
            if(is_file($layoutFile))
            {
                require_once $layoutFile;
            }
            else
            {
                throw new \Exception("Не найден шаблон {$this->layout}", 500);
            }
        }
    }

    public function getMeta()
    {
        $output = '<title>' . $this->meta['title'] . '</title>' . PHP_EOL;
        $output .= '<meta name="discription" content=" ' . $this->meta['desc'] . '">';
        $output .= '<meta name="keywords" content=" ' . $this->meta['keywords'] . '">';
        return $output;
    }
}