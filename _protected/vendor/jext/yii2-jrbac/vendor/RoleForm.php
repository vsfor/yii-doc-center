<?php
namespace jext\jrbac\vendor;

use yii\base\Model;

class RoleForm extends Model
{
    public $name;
    public $description;
    public $isNewRecord;

    public function rules()
    {
        return [
            [['name', 'description'], 'string', 'max' => 32],
            [['name', 'description'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '角色唯一标识',
            'description' => '角色描述',
        ];
    }
}