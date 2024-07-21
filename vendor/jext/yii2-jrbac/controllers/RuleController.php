<?php
namespace jext\jrbac\controllers;

use jext\jrbac\src\RuleForm;
use yii\data\ArrayDataProvider;

/**
 * 规则管理
 * Class RuleController
 * @package jext\jrbac\controllers
 */
class RuleController extends ControllerJrbac
{
    /** 规则列表 */
    public function actionIndex()
    {
        $auth = \Yii::$app->getAuthManager();
        $items = $auth->getRules();
        $dataProvider = new ArrayDataProvider();
        $dataProvider->setModels($items);
        return $this->render('index',[
            'dataProvider' => $dataProvider,
        ]);
    }

    /** 创建规则 */
    public function actionCreate()
    {
        $model = new RuleForm();
        $model->isNewRecord = true;
        if ($model->load(\Yii::$app->getRequest()->post())) {
            $className = trim($model->className);
            if (class_exists($className)) {
                $item = new $className;
                if (trim($model->name)) {
                    $item->name = trim($model->name);
                }
                $auth = \Yii::$app->getAuthManager();
                if ($auth->getRule($item->name)) {
                    $model->addError('className', '同类规则已存在');
                } else if ($auth->add($item)) {
                    return $this->redirect(['index']);
                } else {
                    $model->addError('className', '保存失败');
                }
            } else {
                $model->addError('className', '没有找到类:'.$className);
            }
        }
        return $this->render('create',[
            'model'=>$model
        ]);
    }

    /** 更新规则 */
    public function actionUpdate($id)
    {
        $name = $id;
        $model = new RuleForm();
        $model->isNewRecord = false;
        $auth = \Yii::$app->getAuthManager();
        $item = $auth->getRule($name); 
        if ($model->load(\Yii::$app->getRequest()->post())) {
            $className = trim($model->className);
            if ($item::className() == $className && $item->name == trim($model->name)) {
                return $this->redirect(['index']);
            } else if ($item::className() == $className) {
                $item->name = trim($model->name);
                if($auth->update($name,$item)) {
                    return $this->redirect(['index']);
                } else {
                    $model->addError('name', '保存失败');
                }
            } else {
                if (class_exists($className)) {
                    $item = new $className;
                    if (trim($model->name)) {
                        $item->name = trim($model->name);
                    }
                    if($auth->update($name,$item)) {
                        return $this->redirect(['index']);
                    } else {
                        $model->addError('className', '保存失败');
                    }
                } else {
                    $model->addError('className', '没有找到这个类');
                }
            }

        }

        $model->name = $name;
        $model->className = $item::className();
        return $this->render('update',[
            'model' => $model
        ]);
    }

    /** 删除规则 */
    public function actionDelete($id = '')
    {
        $name = $id;
        if (\Yii::$app->getRequest()->getIsPost()) {
            $auth = \Yii::$app->getAuthManager();
            if ($name) {
                $item = $auth->getRule($name);
                if($item) $auth->remove($item);
            } else {
                if (isset($_POST['names']) && is_array($_POST['names'])) {
                    $flag = true;
                    try {
                        foreach ($_POST['names'] as $name) {
                            if (!$auth->remove($auth->getRule(trim($name)))) $flag = false;
                        }
                        return $flag ? 1 : 0;
                    } catch(\Exception $e) {
                        return 0;
                    }
                }
            }
        }
        return $this->redirect(['index']);
    }

    /** 查看规则详细信息 */
    public function actionView($id)
    {
        $name = $id;
        $auth = \Yii::$app->getAuthManager();
        $item = $auth->getRule($name);
        return $this->render('view',[
            'item' => $item
        ]);
    }

    /** 查看规则权限关联列表 */
    public function actionPermissionindex($id)
    {
        $auth = \Yii::$app->getAuthManager();
        $rule = $auth->getRule($id);
        $allItems = $auth->getPermissions();
        $ruleItems = $auth->getPermissionsByRule($id);
        $dataProvider = new ArrayDataProvider();
        $dataProvider->setModels($allItems);
        return $this->render('permissionindex',[
            'dataProvider' => $dataProvider,
            'allItems' => $allItems,
            'ruleItems' => $ruleItems,
            'rule' => $rule
        ]);
    }

    /** 设置规则关联权限 */
    public function actionSetpermission($name)
    { 
        if (\Yii::$app->getRequest()->getIsPost() && isset($_POST['act'],$_POST['val'])) {
            $auth = \Yii::$app->getAuthManager();
            $permission = $auth->getPermission($_POST['val']);
            try {
                $permission->ruleName = $name;
                $flag = true;
                if($_POST['act'] == 'add') {
                    $permission->ruleName = $name;
                    if (!$auth->update($permission->name, $permission)) $flag = false;
                } else if($_POST['act'] == 'del') {
                    $permission->ruleName = NULL;
                    if (!$auth->update($permission->name, $permission)) $flag = false;
                } else {
                    $flag = false;
                }
                return $flag ? 1 : 0;
            } catch(\Exception $e) {
                return 0;
            }
        }
        return $this->redirect(['index']);
    }

}
