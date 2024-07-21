<?php
namespace app\console\controllers;

use yii\console\Controller;
use yii\helpers\VarDumper;

class TntController extends Controller
{
    public function actionBoom($msg1 = 'hello', $msg2='world')
    {
        VarDumper::dump($_SERVER['argc']);
        echo PHP_EOL;
        VarDumper::dump($_SERVER['argv']);
        echo PHP_EOL;
        echo $msg1.','.$msg2.'~'.PHP_EOL;
    }

}
