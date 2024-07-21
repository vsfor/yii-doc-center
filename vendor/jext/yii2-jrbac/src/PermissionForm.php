<?php
namespace jext\jrbac\src;

use yii\base\Model;

class PermissionForm extends Model
{
    public $name;
    public $description;
    public $ruleName;
    public $isNewRecord;

    public function rules()
    {
        return [
            [['name', 'ruleName', 'description'], 'string', 'max' => 64],
            [['name', 'ruleName', 'description'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '资源唯一标识',
            'description' => '资源描述',
            'ruleName' => '关联规则',
        ];
    }
}