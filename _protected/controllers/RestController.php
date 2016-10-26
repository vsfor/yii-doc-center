<?php
namespace app\controllers;

use app\components\RestCurl;
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
            'msg' => '处理失败'
        ];
        if (Yii::$app->getRequest()->getIsAjax() && Yii::$app->getRequest()->getIsPost()) {
            if (Yii::$app->getRequest()->validateCsrfToken()) {
                $rest = (new RestCurl())->handleRest($_POST);
                $ret['code'] = $rest->response->statusCode;
                $ret['header'] = $rest->request->headers->toArray();
                $ret['data'] = $rest->response->content;
                $ret['msg'] = $rest->response->isOk ? '成功' : '失败';
            } else {
                $ret['code'] = 500;
                $ret['msg'] = '处理异常';
            }
        }
        return Json::encode($ret,JSON_PRETTY_PRINT);
    }
 

}
