<?php
namespace app\models;

use yii\base\Model;
use Yii;

/**
 * Activate request form.
 */
class ActivateRequestForm extends Model
{
    public $email;

    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\app\models\User',
                'filter' => ['status' => User::STATUS_INACTIVE],
                'message' => Yii::t('app', 'Wrong email.')
            ],
        ];
    }

    /**
     * Returns the attribute labels.
     *
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('app', 'Email'),
        ];
    }


    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool Whether the email was send.
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne(['status' => User::STATUS_INACTIVE, 'email' => $this->email]);

        if (!$user) {
            return false;
        }

        if (!$user->account_activation_token) {
            $user->generateAccountActivationToken();

            if (!$user->save()) {
                return false;
            }
        }

        return Yii::$app->mailer->compose('accountActivationToken', ['user' => $user])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account activation for ' . Yii::$app->name)
            ->send();

    }
}
