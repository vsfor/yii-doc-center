<?php
namespace jext\jrbac\controllers;

use jext\jrbac\src\JDbManager;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use Yii;

/**
 * Class ControllerJrbac
 * @package jext\jrbac\controllers
 */
class ControllerJrbac extends Controller
{
    public $layout = 'jrbac';

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (\Yii::$app->getUser()->getIsGuest()) {
                throw new BadRequestHttpException('请先登录',400);
            }
            /** @var JDbManager $auth */
            $auth = Yii::$app->getAuthManager();
            $user = Yii::$app->getUser();

            if ($auth->isRoot()) {
                return true;
            }

            $permissionName = $auth->getPermissionName($action);
            $params = ArrayHelper::merge(
                Yii::$app->getRequest()->get(),
                Yii::$app->getRequest()->post()
            ); //注意 此处只支持 form 参数提交方式

            if ($auth->allow($permissionName, $params)) {
                return true;
            }
        }
        throw new BadRequestHttpException('权限不足',400);
    }
}
