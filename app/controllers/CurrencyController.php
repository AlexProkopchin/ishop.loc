<?php

namespace app\controllers;

use app\models\Cart;
use ishop\App;

class CurrencyController extends AppController
{
    public function changeAction()
    {
/*         $currency = !empty($_GET['curr']) ? $_GET['curr'] : null;
        if($currency)
        {
            $curr = App::$app->getProperty('currency');
            if(!empty($curr['code']))
            {
                setcookie('currency', $currency, time() + 3600*24*7, '/');
                Cart::recalc($curr);
            }
        }
        redirect(); */
        $currency = !empty($_GET['curr']) ? $_GET['curr'] : null;
        if($currency){
            $curr = \R::findOne('currency', 'code = ?', [$currency]);
            if(!empty($curr)){
                setcookie('currency', $currency, time() + 3600*24*7, '/');
                Cart::recalc($curr);
            }
        }
        redirect();  
    }
}