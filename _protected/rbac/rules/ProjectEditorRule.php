<?php
namespace app\rbac\rules;

use app\components\ProjectLib;
use app\models\ProjectMember;
use yii\rbac\Rule;

class ProjectEditorRule extends Rule
{
    public $name = 'isProjectEditor';

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

        $pm = ProjectLib::getInstance()->getMemberLevel(intval($params['project_id']), $user);

        if (!$pm || $pm['level'] < ProjectMember::LEVEL_EDITOR) {
            return false;
        }

        return true;
    }
    
}