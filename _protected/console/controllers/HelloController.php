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
        $rows = User::find()->where([
            'id'=>[1,2]
        ])->asArray()->all();
        Jeen::echoln($rows);
    }
    
    public function actionTc()
    {
        $t = \Yii::$app->security->generateRandomString();
        Jeen::echoln($t);
    }

    public function actionSphinx()
    {
        /** @var \yii\sphinx\Connection $s */
        $s = \Yii::$app->sphinx;
        $q = new \yii\sphinx\Query();
        $rows = $q->from('test1')
            ->select(['id','group_id'])
            ->match('电影吃饭')
            ->where(['group_id' => [1,2]])
            ->andWhere(['>','id',3])
            ->all();
        Jeen::echoln($rows);
    }

    public function actionXs()
    {
//        $model = new \app\components\xunsearch\Test();
//        $model->id = 5;
//        $model->title = '中文标题';
//        $model->content = '今天只吃饭,不看电影';
//        $model->status = 1;
//        if ($model->save()) {
//            Jeen::echoln($model->toArray());
//        } else {
//            Jeen::echoln($model->getErrors());
//        }
        
//        $a = \app\components\xunsearch\Test::findOne(2);
//        $a->content .= ' update';
//        $a->addIndex('content', '电影');
//        $a->save();
//        Jeen::echoln($a->toArray());
        
        $q = \app\components\xunsearch\Test::find();
//        $q->getDb()->getIndex()->flushIndex();
        $t = $q->where('电影')->asArray()->all();
        Jeen::echoln($t);
        
        $db = \app\components\xunsearch\Test::getDb();
        $scws = $db->getScws();
        $t = $scws->getResult('吃饭看电影');
        Jeen::echoln($t);
        
        $index = $db->getIndex();
        $t = $index->getProject();
        Jeen::echoln($t);
        
        $search = $db->getSearch();
        $t = $search->getDbTotal();
        Jeen::echoln($t);
        
        
    }

}
