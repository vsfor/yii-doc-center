<?php
namespace jext\jrbac\src;

use yii\base\Model;

class RuleForm extends Model
{
    public $name;
    public $className;
    public $isNewRecord;

    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 64],
            [['className'], 'string', 'max' => 255],
            [['name', 'className'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '规则唯一标识',
            'className' => '规则类名',
        ];
    }
}