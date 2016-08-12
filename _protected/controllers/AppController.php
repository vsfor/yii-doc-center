<?php
namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use Yii;

/**
 * AppController extends Controller and implements the behaviors() method
 * where you can specify the access control ( AC filter + RBAC ) for your controllers and their actions.
 */
class AppController extends Controller
{
    /**
     * Returns a list of behaviors that this component should behave as.
     * Here we use RBAC in combination with AccessControl filter.
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'controllers' => ['user', 'article'],
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'admin'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'controllers' => ['article'],
                        'actions' => ['create', 'update', 'admin'],
                        'allow' => true,
                        'roles' => ['employee'],
                    ],
                    [
                        'controllers' => ['article'],
                        'actions' => ['index', 'view'],
                        'allow' => true
                    ],
                    [
                        'controllers' => ['project','page','catalog','template'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'controllers' => ['user'],
                        'actions' => ['profile', 'update-profile', 'upload-image'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],

                ], // rules

            ], // access

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ], // verbs

        ]; // return

    } // behaviors

    public function beforeAction($action)
    {
        return parent::beforeAction($action);
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

} // AppController
