<?php
namespace jext\jrbac\vendor;

use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\rbac\DbManager;
use yii\rbac\Item;

/**
 * 注意: allow 是对  can 方法的重写
 * 默认采用  /module/controller/action 的组合方式 声明权限资源名 , * 星号 代表通配符
 * 如:  /module/controller/*  代表模块控制器中的所有方法 权限资源
 *
 * 自定义资源名  请注意  不要包含斜杠 /  及 星号 *  ,否则可能导致权限验证异常
 *
 * Class JDbManager
 * @package jext\jrbac\vendor
 */
class JDbManager extends DbManager implements JManagerInterface
{
    public function isRoot()
    {
        if(\Yii::$app->getUser()->getIsGuest()) return false;
        $user = \Yii::$app->getUser()->getIdentity();
        if($user->getId() == 1) return true;
        $auth = \Yii::$app->getAuthManager();
        $roles = $auth->getRolesByUser($user->getId());
        $roleNames = ArrayHelper::getColumn($roles,'name',false);
        return in_array('root',$roleNames);
    }

    public function getUserQuery()
    {
        $userModel = \Yii::$app->getUser()->identityClass;
        return $userModel::find();
    }

    public function getUsersByRole($roleName)
    {
        if (empty($roleName)) {
            return [];
        }

        $query = (new Query())->select('*')
            ->from($this->assignmentTable)
            ->where(['item_name' => (string) $roleName]);

        $users = [];
        $userModel = \Yii::$app->getUser()->identityClass;
        foreach ($query->all($this->db) as $row) {
            $user = $userModel::findOne($row['user_id']);
            if($user) $users[$row['user_id']] = $user;
        }
        return $users;
    }

    public function getUserIdsByRole($roleName)
    {
        if (empty($roleName)) {
            return [];
        }

        $query = (new Query())->select('user_id')
            ->from($this->assignmentTable)
            ->where(['item_name' => (string) $roleName]);
        $rows = $query->all($this->db);
        if($rows) {
            return ArrayHelper::getColumn($rows,'user_id');
        }
        return [];
    }

    public function getPermissionsByRule($ruleName)
    {
        $query = (new Query)->from($this->itemTable)->where([
            'type' => Item::TYPE_PERMISSION,
            'rule_name' => $ruleName,
        ]);
        $permissions = [];
        foreach ($query->all($this->db) as $row) {
            $permissions[$row['name']] = $this->populateItem($row);
        }
        return $permissions;
    }
 
    public function getPermissionName($action)
    {
        $appId = \Yii::$app->id;
        $moduleId = $action->controller->module->id;
        $controllerId = $action->controller->id;
        $actionId = $action->id;
        return $appId == $moduleId ? "/$controllerId/$actionId" : "/$moduleId/$controllerId/$actionId";
    }
    
    public function allow($permission, $params=[])
    {
        if($this->isRoot()) return true;
        $urlArr = explode('/',$permission);
        $user = \Yii::$app->getUser();
        if (count($urlArr) == 4) {
            $module = $urlArr[1];
            $controller = $urlArr[2];
            if($user->can("/$module/*/*", $params) || $user->can("/$module/$controller/*", $params)) {
                return true;
            }
        } else if (count($urlArr) == 3) {
            $controller = $urlArr[1];
            if($user->can("/$controller/*", $params)) {
                return true;
            }
        }

        return $user->can($permission, $params);
    }

}