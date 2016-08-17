<?php
namespace app\rbac\helpers;

use app\models\User;
use app\rbac\models\Role;
use Yii;

/**
 * RBAC helper class.
 */
class RbacHelper
{
    /**
     * In development environment we want to give theCreator role to the first signed up user.
     * This user should be You. 
     * If user is not first, there is no need to automatically give him role, his role is authenticated user '@'.
     * In case you want to give some of your custom roles to users by default, this is a good place to do it.
     *
     * @param  integer $id The id of the registered user.
     * @return boolean     True if theCreator role is assigned or if there was no need to do it.
     */
    public static function assignRole($id)
    {
        $auth = Yii::$app->getAuthManager();

        $roles = $auth->getRolesByUser($id);
        if (isset($roles['siteMember'])) {
            return true;
        }

        $role = $auth->getRole('siteMember');
        $info = $auth->assign($role, $id);

        return ($info->roleName == "siteMember") ? true : false ;
    }
}

