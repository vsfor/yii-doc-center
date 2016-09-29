<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "{{%oauth_wx}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $access_token
 * @property integer $expire_time
 * @property string $refresh_token
 * @property string $openid
 * @property string $unionid
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $info_time
 * @property string $info_data
 * @property integer $status
 */
class OauthWx extends \yii\db\ActiveRecord
{
    const STATUS_NORMAL = 1;
    const STATUS_DELETE = 9;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%oauth_wx}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'expire_time', 'created_at', 'updated_at', 'info_time', 'status'], 'integer'],
            [['info_data'], 'string'],
            [['access_token', 'refresh_token'], 'string', 'max' => 255],
            [['openid', 'unionid'], 'string', 'max' => 63],
        ];
    }

    /**
     * Returns a list of behaviors that this component should behave as.
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '关联用户',
            'access_token' => 'Access Token',
            'expire_time' => '过期时间',
            'refresh_token' => 'Refresh Token',
            'openid' => 'Openid',
            'unionid' => 'Unionid',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'info_time' => 'Info Time',
            'info_data' => 'Info Data',
            'status' => 'Status',
        ];
    }

    public function beforeSave($insert)
    {
        if (is_null($this->user_id)) $this->user_id = 0;
        if (!$this->status) $this->status = self::STATUS_NORMAL;
        return parent::beforeSave($insert);
    }
}
