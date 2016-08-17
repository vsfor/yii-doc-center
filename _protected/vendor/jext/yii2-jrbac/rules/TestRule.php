<?php
namespace jext\jrbac\rules;
 
use yii\rbac\Rule;

class TestRule extends Rule
{
    public $name = 'isTest';

    /**
     * @param int|string $user 用户ID
     * @param \yii\rbac\Item $item  Permission
     * @param array $params  传入参数
     * @return bool
     */
    public function execute($user, $item, $params)
    {
        if (isset($params['userid']) && $params['userid'] == $user) {
            return true;
        }
        return false;
    }

}