<?php
namespace app\rbac\rules;

use app\models\User;
use yii\rbac\Rule;

class CreateProjectRule extends Rule
{
    public $name = 'canCreateProject';

    /**
     * @param  string|integer $user   The user ID.
     * @param  Item           $item   The role or permission that this rule is associated with
     * @param  array          $params Parameters passed to ManagerInterface::checkAccess().
     * @return boolean                A value indicating whether the rule permits the role or
     *                                permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        if (!isset($params['project_id'])) {
            return false;
        }

        $userModel = User::findOne($user);
        if ($userModel && $userModel->project_limit > 0) {
            return true;
        }

        return false;
    }
    
}