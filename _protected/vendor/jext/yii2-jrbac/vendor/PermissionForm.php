<?php
namespace jext\jrbac\vendor;

use yii\base\Model;

class PermissionForm extends Model
{
    public $name;
    public $description;
    public $ruleName;
    public $parentPermission;
    public $isNewRecord;

    public function rules()
    {
        return [
            [['description'], 'string'],
            [['name', 'ruleName', 'parentPermission'], 'string', 'max' => 64],
            [['name', 'ruleName', 'description', 'parentPermission'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '资源唯一标识',
            'description' => '资源描述',
            'ruleName' => '关联规则',
            'parentPermission' => '父级资源',
        ];
    }
}