<?php
namespace jext\jrbac\src;

use yii\rbac\ManagerInterface;

interface JManagerInterface extends ManagerInterface
{
    /**
     * Returns the users that are assigned to the role via [[assign()]].
     * @param string $roleName the role name (see [[\yii\rbac\Role::name]])
     * @return \yii\web\User[] all roles directly or indirectly assigned to the user.
     */
    public function getUsersByRole($roleName);

    /**
     * Returns all permissions that the specified role represents.
     * @param string $ruleName the rule name (see [[\yii\rbac\Rule::name]])
     * @return \yii\rbac\Permission[] all permissions that the rule represents. The array is indexed by the permission names.
     */
    public function getPermissionsByRule($ruleName);

    /**
     * check the visitor is Root or not
     * @return bool
     */
    public function isRoot();
}