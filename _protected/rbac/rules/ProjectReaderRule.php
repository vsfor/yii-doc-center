<?php
namespace app\rbac\rules;

use app\components\ProjectLib;
use yii\rbac\Rule;

class ProjectReaderRule extends Rule
{
    public $name = 'isProjectReader';

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
        
        /** @var \app\models\Project $p */
        $p = ProjectLib::getInstance()->findModel(intval($params['project_id']));
        if ($p) {
            if($p->open_type == $p::OPEN_TO_ALL) {
                return true;
            } elseif ($p->open_type == $p::OPEN_TO_USER && $user) {
                return true;
            }
        }

        $pm = ProjectLib::getInstance()->getMemberLevel(intval($params['project_id']), $user);

        if (!$pm) {
            return false;
        }

        return true;
    }
    
}