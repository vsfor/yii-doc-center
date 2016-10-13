<?php
namespace app\controllers;

use yii\helpers\Json;
use yii\web\Controller;
use Yii;

/**
 * Site controller.
 * It is responsible for displaying static pages, logging users in and out,
 * sign up and account activation, and password reset.
 */
class RestController extends Controller
{
    public $layout = 'fullPage.php';

 
    /**
     * 接口测试首页
     * 
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionSendrest()
    {
        $ret = [
            'code' => 400,
            'header' => '',
            'data' => '',
            'msg' => '请求失败'
        ];
        if (Yii::$app->getRequest()->getIsAjax() && Yii::$app->getRequest()->getIsPost()) {
            if (Yii::$app->getRequest()->validateCsrfToken()) {
                $ret = $_POST;
            } else {
                $ret['code'] = 500;
            }
        }
        return Json::encode($ret);
    }
 

}
