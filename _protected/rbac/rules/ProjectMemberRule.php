<?php
namespace app\rbac\rules;

use app\components\ProjectLib;
use yii\rbac\Rule;

class ProjectMemberRule extends Rule
{
    public $name = 'isProjectMember';

    /**
     * @param  string|integer $user   The user ID.
     * @param  Item           $item   The role or permission that this rule is associated with
     * @param  array          $params Parameters passed to ManagerInterface::checkAccess().
     * @return boolean                A value indicating whether the rule permits the role or
     *                                permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        $project_id = intval($params['project_id']);
        $projectLib = ProjectLib::getInstance();
        $memberIds = $projectLib->getMemberIds($project_id);
        return in_array($user, $memberIds);
    }
}