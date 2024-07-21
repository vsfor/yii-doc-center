<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\console\controllers;


use app\models\UserIdentity;
use jext\jrbac\vendor\JMenu;
use Yii;
use app\components\Jeen;
use app\models\User;
use yii\console\Controller;
class TntController extends Controller
{
    public function actionBoom()
    {
        Jeen::echoln("user Dsn:".User::getDb()->dsn);
        if (!$this->confirm('sure?')) exit('bye~'.PHP_EOL);

        $u = new UserIdentity();
        $u->username = 'wx.'.microtime(true);
//        $u->email = null;
        if (!$u->save()) {
            Jeen::echoln($u->getErrors());
        } else {
            Jeen::echoln($u->toArray());
        }
    }

    public function actionTest()
    {
        $t = JMenu::getInstance()->getMenu();
        Jeen::echoln($t);
    }
    
}
