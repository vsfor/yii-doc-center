<?php
namespace jext\jrbac\src\rules;
 
use yii\rbac\Rule;

class AdminSelfRule extends Rule
{
    public $name = 'isAdminSelf';

    /**
     * @param int|string $user 用户ID
     * @param \yii\rbac\Item $item  Permission|Role
     * @param array $params  传入参数
     * @return bool
     */
    public function execute($user, $item, $params)
    {
        if (isset($params['user_id']) && $params['user_id'] == $user) {
            return true;
        }
        return false;
    }

}