<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\console\controllers;

use Yii;
use app\components\Jeen;
use app\models\User;
use yii\console\Controller;

/**
 * This command echoes the first argument that you have entered.
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";
    }
    
    public function actionTc()
    {
        $t = \Yii::$app->security->generateRandomString();
        Jeen::echoln($t);
    }
    
    //内测资格邮件
    public function actionBe()
    {
        Yii::$app->urlManager->setScriptUrl("http://ydc.jeen.wang/");
        /** @var $user User */
        $user = User::find()->where('`id`=:id',[
            ':id'=>1002//1003
        ])->one();
        if (!$user) {
            Jeen::echoln('User Not Found');
            exit();
        }
        $ret = Yii::$app->mailer->compose('betaUserTip', ['user' => $user])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($user->email)
            ->setSubject('Beta Usage Tip For ' . Yii::$app->name)
            ->send();
        Jeen::echoln($ret);
        exit();
    }
    
}
