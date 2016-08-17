<?php
namespace app\controllers;

use app\components\Jeen;
use app\models\User;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper; 
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use Yii;

/**
 * 基础控制器
 *
 * 使用JRBAC  进行网站权限控制
 */
class ControllerBase extends Controller
{
    public $noJRBAC = false; //没使用JRBAC 模块进行权限控制时的逻辑开关

/*
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
*/

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            //JRBAC 权限检测
            /** @var \jext\jrbac\vendor\JDbManager $auth */
            $auth = Yii::$app->getAuthManager();
            $user = Yii::$app->getUser();
            $permissionName = $auth->getPermissionName($action);

            if ($user->getIsGuest()) {
                if ($auth->hasChild($auth->getRole('guest'), $auth->getPermission($permissionName))) {
                    return true;
                } else {
                    throw new BadRequestHttpException('无权访问该页面,请先登录',400);
                }
            } else {
                if ($user->getIdentity()->status == User::STATUS_DELETED) {
                    throw new BadRequestHttpException('账号已停用,请联系系统管理员',400);
                }

                $params = ArrayHelper::merge(
                    Yii::$app->getRequest()->get(),
                    Yii::$app->getRequest()->post()
                ); //注意 此处只支持 form 参数提交方式

                if ($auth->allow($permissionName, $params)) {
                    return true;
                }
            } 
        }
        echo 'Permission Denied~!';
        return false;
    }

    public function afterAction($action, $result)
    {
        return parent::afterAction($action, $result);
    }

    protected function initGoBackUrl()
    {
        Yii::$app->getSession()->set(Yii::$app->getUser()->returnUrlParam,[
            \Yii::$app->getRequest()->getUrl()
        ]);
    }

}
