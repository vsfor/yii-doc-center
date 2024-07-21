<?php
namespace jext\jrbac\controllers;
 
use jext\jrbac\src\RoleForm;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

/**
 * 角色管理
 * Class RoleController
 * @package jext\jrbac\controllers
 */
class RoleController extends ControllerJrbac
{
    /** 角色列表 */
    public function actionIndex()
    {
        $auth = \Yii::$app->getAuthManager();
        $items = $auth->getRoles();
        $dataProvider = new ArrayDataProvider();
        $dataProvider->setModels($items);
        return $this->render('index',[
            'dataProvider' => $dataProvider,
        ]);
    }

    /** 创建角色 */
    public function actionCreate()
    {
        $model = new RoleForm();
        $model->isNewRecord = true;
        if($model->load(\Yii::$app->getRequest()->post())) {
            $auth = \Yii::$app->getAuthManager();
            if($auth->getRole($model->name)) {
                $model->addError('name','角色标识已存在');
            } else {
                $item = $auth->createRole($model->name);
                $item->description = $model->description;
                if($auth->add($item)) {
                    return $this->redirect(['index']);
                }
            }
        }
        return $this->render('create',[
            'model'=>$model
        ]);
    }

    /** 删除角色 */
    public function actionDelete($id = '')
    {
        $name = $id;
        if (\Yii::$app->getRequest()->getIsPost()) {
            $auth = \Yii::$app->getAuthManager();
            if($name) {
                $item = $auth->getRole($name);
                if($item) $auth->remove($item);
            } else {
                if(isset($_POST['names']) && is_array($_POST['names'])) {
                    $flag = true;
                    try {
                        foreach($_POST['names'] as $name) {
                            if(!$auth->remove($auth->getRole(trim($name)))) $flag = false;
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

    /** 更新角色 */
    public function actionUpdate($id)
    {
        $name = $id;
        $model = new RoleForm();
        $model->isNewRecord = false;
        $auth = \Yii::$app->getAuthManager();
        $item = $auth->getRole($name);
        if($model->load(\Yii::$app->getRequest()->post())) {
            $item->name = $model->name;
            $item->description = $model->description;
            if($auth->update($name,$item)) {
                return $this->redirect(['index']);
            }
        }
        $model->name = $name;
        $model->description = $item->description;
        return $this->render('update',[
            'model' => $model
        ]);
    }

    /** 查看角色详细信息 */
    public function actionView($id)
    {
        $name = $id;
        $auth = \Yii::$app->getAuthManager();
        $item = $auth->getRole($name);
        return $this->render('view',[
            'item' => $item
        ]);
    }

    /** 查看角色用户列表 */
    public function actionUserindex($id)
    {
        $auth = \Yii::$app->getAuthManager();
        $role = $auth->getRole($id);
        $roleUserIds = $auth->getUserIdsByRole($id);
        $dataProvider = new ActiveDataProvider([
            'query' => $auth->getUserQuery()->where('`status`!=9'),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        return $this->render('userindex',[
            'dataProvider' => $dataProvider,
            'role' => $role,
            'roleUserIds' => $roleUserIds
        ]);
    }

    /** 设置角色用户 */
    public function actionSetuser($name)
    {
        if (\Yii::$app->getRequest()->getIsPost() && isset($_POST['act'],$_POST['val'])) {
            $auth = \Yii::$app->getAuthManager();
            $role = $auth->getRole($name);
            try {
                $flag = true;
                if($_POST['act'] == 'add') {
                    if(!$auth->assign($role,$_POST['val'])) $flag = false;
                } else if($_POST['act'] == 'del') {
                    if(!$auth->revoke($role,$_POST['val'])) $flag = false;
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

    /** 查看角色权限列表 */
    public function actionPermissionindex($id)
    {
        $auth = \Yii::$app->getAuthManager();
        $role = $auth->getRole($id);
        $allItems = $auth->getPermissions();
        $roleItems = $auth->getPermissionsByRole($id);
        $ownItems = $auth->getChildren($id);
//        var_dump($roleItems);exit();
        $dataProvider = new ArrayDataProvider();
        $dataProvider->setModels($allItems);
        return $this->render('permissionindex',[
            'dataProvider' => $dataProvider,
            'allItems' => $allItems,
            'roleItems' => $roleItems,
            'ownItems' => $ownItems,
            'role' => $role
        ]);
    }

    /** 设置角色权限 */
    public function actionSetpermission($name)
    { 
        if (\Yii::$app->getRequest()->getIsPost() && isset($_POST['act'],$_POST['val'])) {
            $auth = \Yii::$app->getAuthManager();
            $role = $auth->getRole($name);
            $permission = $auth->getPermission($_POST['val']);
            try {
                $flag = true;
                if($_POST['act'] == 'add') {
                    if(!$auth->addChild($role,$permission)) $flag = false;
                } else if($_POST['act'] == 'del') {
                    if(!$auth->removeChild($role,$permission)) $flag = false;
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

    /** 子角色列表 */
    public function actionSubindex($id)
    {
        $auth = \Yii::$app->getAuthManager();
        $role = $auth->getRole($id);
        $allItems = $auth->getRoles();
        $subItems = $auth->getChildren($id);
        $dataProvider = new ArrayDataProvider();
        $dataProvider->setModels($allItems);
        return $this->render('subindex',[
            'dataProvider' => $dataProvider,
            'allItems' => $allItems,
            'subItems' => $subItems,
            'role' => $role
        ]);
    }

    /** 子角色关联设置 */
    public function actionSetsub($name)
    {
        if (\Yii::$app->getRequest()->getIsPost() && isset($_POST['act'],$_POST['val'])) {
            $auth = \Yii::$app->getAuthManager();
            $role = $auth->getRole($name);
            $permission = $auth->getRole($_POST['val']);
            try {
                $flag = true;
                if($_POST['act'] == 'add') {
                    if(!$auth->addChild($role,$permission)) $flag = false;
                } else if($_POST['act'] == 'del') {
                    if(!$auth->removeChild($role,$permission)) $flag = false;
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
