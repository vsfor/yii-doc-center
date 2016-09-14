<?php
namespace app\controllers;

use app\components\Jeen;
use app\models\User;
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

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            //JRBAC 权限检测
            /** @var \jext\jrbac\vendor\JDbManager $auth */
            $auth = Yii::$app->getAuthManager();
            $user = Yii::$app->getUser();
            $permissionName = $auth->getPermissionName($action);
            $params = ArrayHelper::merge(
                Yii::$app->getRequest()->get(),
                Yii::$app->getRequest()->post()
            ); //注意 此处只支持 form 参数提交方式

            if ($user->getIsGuest()) {
                $permission = $auth->getPermission($permissionName);
                if ($auth->hasChild($auth->getRole('guest'), $permission)) {
                    if ($permission->ruleName) {
                        $rule = $auth->getRule($permission->ruleName);
                        return $rule ? $rule->execute(0, $permission, $params) : false;
                    } else {
                        return true;
                    }
                } else {
                    throw new BadRequestHttpException('无权访问该页面,请先登录',400);
                }
            } else {
                if ($user->getIdentity()->status == User::STATUS_DELETED) {
                    throw new BadRequestHttpException('账号已停用,请联系系统管理员',400);
                }

                if ($auth->allow($permissionName, $params)) {
                    return true;
                }
            } 
        }
        Yii::$app->getSession()->setFlash('error','Permission Denied ! Please Contact the System Administrator ~!');
//        if(Yii::$app->getUser()->getReturnUrl() == Yii::$app->getRequest()->getUrl()) {
//            return $this->goHome();
//        } else {
//            return $this->goBack();
//        }
        return $this->goHome();
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
