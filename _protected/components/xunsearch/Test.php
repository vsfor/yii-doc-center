<?php
namespace app\components\xunsearch;

use hightman\xunsearch\ActiveRecord;

/**
 * Class Test
 * @package app\components\xunsearch
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $status
 *
 */
class Test extends ActiveRecord
{
    public static function projectName()
    {
        return 'test';
    }
}