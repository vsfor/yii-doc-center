<?php
namespace jext\jrbac\controllers;

use jext\jrbac\src\JAction;
use jext\jrbac\src\PermissionForm;
use yii\base\Module;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

/** 权限资源管理
 * Class PermissionController
 * @package admin\modules\jrbac\controllers
 */
class PermissionController extends ControllerJrbac
{
    /** 查看资源列表 */
    public function actionIndex()
    {
        $auth = \Yii::$app->getAuthManager();
        $items = $auth->getPermissions();
        $dataProvider = new ArrayDataProvider();
        $dataProvider->setModels($items);
        $lockFile = \Yii::getAlias('@runtime/jrbac-permission-init.lock');
        if (file_exists($lockFile)) {
            chmod($lockFile, 0777);
            $lastTime = file_get_contents($lockFile);
        } else {
            touch($lockFile);
            $lastTime = 0;
        }
        return $this->render('index',[
            'dataProvider' => $dataProvider,
            'lastTime' => $lastTime,
        ]);
    }

    /** 添加权限资源 */
    public function actionCreate()
    {
        $model = new PermissionForm();
        $model->isNewRecord = true;
        if($model->load(\Yii::$app->getRequest()->post())) {
            $model->name = trim($model->name);
            if (!$model->name) {
                $model->addError('name', '资源标识不可为空');
            } else {
                $auth = \Yii::$app->getAuthManager();
                if($auth->getPermission($model->name)) {
                    $model->addError('name','资源标识已存在');
                } else {
                    $item = $auth->createPermission($model->name);
                    $item->description = trim($model->description);
                    $item->ruleName = trim($model->ruleName) ? : NULL;

                    if($auth->add($item)) {
                        return $this->redirect(['index']);
                    }
                }
            }
        }
        $rules = ArrayHelper::getColumn(\Yii::$app->getAuthManager()->getRules(),"name");
        return $this->render('create',[
            'model'=>$model,
            'rules'=>$rules,
        ]);
    }

    /** 删除权限资源 */
    public function actionDelete($id='')
    {
        $name = $id;
        if (\Yii::$app->getRequest()->getIsPost()) {
            $auth = \Yii::$app->getAuthManager();
            if($name) {
                $item = $auth->getPermission($name);
                if($item) $auth->remove($item);
            } else {
                if(isset($_POST['names']) && is_array($_POST['names'])) {
                    $flag = true;
                    try {
                        foreach($_POST['names'] as $name) {
                            if(!$auth->remove($auth->getPermission(trim($name)))) $flag = false;
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

    /** 编辑权限资源 */
    public function actionUpdate($id)
    {
        $name = $id;
        $model = new PermissionForm();
        $model->isNewRecord = false;
        $auth = \Yii::$app->getAuthManager();
        $item = $auth->getPermission($name);

        if($model->load(\Yii::$app->getRequest()->post())) {
            $model->name = trim($model->name);
            if (!$model->name) {
                $model->addError('name', '资源标识不可为空');
            } else {
                $item->name = $model->name;
                $item->description = trim($model->description);
                $item->ruleName = trim($model->ruleName)? : NULL;

                if($auth->update($name,$item)) {
                    return $this->redirect(['index']);
                }
            }
        }
        $model->name = $name;
        $model->description = $item->description;
        $model->ruleName = $item->ruleName;

        $rules = ArrayHelper::getColumn($auth->getRules(),"name");

        return $this->render('update',[
            'model' => $model,
            'rules' => $rules,
        ]);
    }

    /** 查看权限资源信息 */
    public function actionView($id)
    {
        $name = $id;
        $auth = \Yii::$app->getAuthManager();
        $item = $auth->getPermission($name);
        $subItems = $auth->getChildren($name);
        return $this->render('view',[
            'item' => $item,
            'subItems' => $subItems
        ]);
    }

    /** 子权限列表 */
    public function actionSubindex($id)
    {
        $auth = \Yii::$app->getAuthManager();
        $permission = $auth->getPermission($id);
        $allItems = $auth->getPermissions();
        $subItems = $auth->getChildren($id);
        $dataProvider = new ArrayDataProvider();
        $dataProvider->setModels($allItems);
        return $this->render('subindex',[
            'dataProvider' => $dataProvider,
            'allItems' => $allItems,
            'subItems' => $subItems,
            'permission' => $permission
        ]);
    }

    /** 子权限关联设置 */
    public function actionSetsub($name)
    {
        if (\Yii::$app->getRequest()->getIsPost() && isset($_POST['act'],$_POST['val'])) {
            $auth = \Yii::$app->getAuthManager();
            $permission = $auth->getPermission($name);
            $sub = $auth->getPermission($_POST['val']);
            try {
                $flag = true;
                if($_POST['act'] == 'add') {
                    if(!$auth->addChild($permission, $sub)) $flag = false;
                } else if($_POST['act'] == 'del') {
                    if(!$auth->removeChild($permission, $sub)) $flag = false;
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


    /** 自动扫描并初始化权限资源列表 */
    public function actionInit()
    {
        if (\Yii::$app->getRequest()->getIsPost() && \Yii::$app->getRequest()->getIsAjax()) {
            $clearExistPermissions = false;//是否清理现有资源列表
            //为true时  会清理以 / 开头的  无有效path匹配的资源

            $permissionList = [];

            //默认模块控制器权限列表 -- Start
            $moduleControllerList = [];
            $f_list = scandir(\Yii::$app->getControllerPath());
            foreach ($f_list as $f_item) {
                if (StringHelper::endsWith($f_item, 'Controller.php')) {
                    $fClassName = explode('.php', $f_item)[0];
                    $moduleControllerList[] = \Yii::$app->controllerNamespace.'\\'.$fClassName;
                }
            }
            $permissions = [];
            if ($moduleControllerList) {
                $permissions = JAction::getInstance()->getPermissionList($moduleControllerList, false);
            }
            $permissionList = ArrayHelper::merge($permissionList, $permissions);
            //默认模块控制器权限列表 -- End

            //自定义模块 -- Start
            $modules = \Yii::$app->getModules(); //配置中的模块
            $excludeModules = ['gii','debug']; //排除模块
            foreach ($modules as $moduleId => $module) {
                if (in_array($moduleId,$excludeModules)) {
                    continue;
                }
                if (!$module instanceof Module) {
                    $module = \Yii::$app->getModule($moduleId);
                }
                unset($module->module);
                $moduleControllerList = [];
                $f_list = scandir($module->getControllerPath());
                foreach ($f_list as $f_item) {
                    if (StringHelper::endsWith($f_item, 'Controller.php')) {
                        $fClassName = explode('.php', $f_item)[0];
                        $moduleControllerList[] = $module->controllerNamespace.'\\'.$fClassName;
                    }
                }
                $permissions = [];
                if ($moduleControllerList) {
                    $permissions = JAction::getInstance()->getPermissionList($moduleControllerList, false);
                }
                $permissionList = ArrayHelper::merge($permissionList, $permissions);
            }

            $auth = \Yii::$app->getAuthManager();
            $existPermissions = $auth->getPermissions();
            if ($clearExistPermissions) {
                //清理符合初始化规则的无效权限资源
                $existPermissionNames = ArrayHelper::getColumn($existPermissions, 'name');
                $permissionNames = ArrayHelper::getColumn($permissionList, 'path');
                foreach ($existPermissionNames as $existPermissionName) {
                    if (StringHelper::startsWith($existPermissionName,'/') && !in_array($existPermissionName,$permissionNames)) {
                        $auth->remove($existPermissions[$existPermissionName]);
                    }
                }
            }

            foreach ($permissionList as $permission) {
                if (isset($existPermissions[$permission['path']])) {
                    //更新已有资源
                    if ($existPermissions[$permission['path']]->description != $permission['description']) {
                        $existPermissions[$permission['path']]->description = $permission['description'];
                        $auth->update($permission['path'], $existPermissions[$permission['path']]);
                    }
                } else {
                    //添加新资源
                    $newPermission = $auth->createPermission($permission['path']);
                    $newPermission->description = $permission['description'];
                    $auth->add($newPermission);
                }
            }

            file_put_contents(\Yii::getAlias('@runtime/jrbac-permission-init.lock'), time());
            exit('上次初始化时间:'.date("Y-m-d H:i"));
        }
        exit("error");
    }

}
