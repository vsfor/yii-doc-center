<?php
namespace app\models;

use yii\base\Model;
use Yii;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;
    public $diyCheck;

    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['name', 'email', 'subject', 'body', 'verifyCode'], 'required'],
            ['email', 'email'],
            ['verifyCode', 'captcha'],
            ['diyCheck', 'checkDiy'],
        ];
    }

    public function checkDiy()
    {
        if (trim($this->diyCheck) == $this->getDiy()) {
            return true;
        }
        $this->addError('diyCheck', '请输入正确的校验信息');
        return false;
    }

    public function getDiy()
    {
        return '你好';
    }

    /**
     * Returns the attribute labels.
     *
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'name'=> Yii::t('app', 'Name'),
            'email' => Yii::t('app', 'Email'),
            'subject' => Yii::t('app', 'Subject'),
            'body' => Yii::t('app', 'Text'),
            'verifyCode' => Yii::t('app', 'Verification Code'),
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param  string $email The target email address.
     * @return bool          Whether the email was sent.
     */
    public function sendEmail($email)
    {
//        return Yii::$app->mailer->compose()
//                                ->setTo($email)
//                                ->setFrom([$this->email => $this->name])
//                                ->setSubject($this->subject)
//                                ->setTextBody($this->body)
//                                ->send();

        return Yii::$app->mailer->compose('contact', ['model' => $this])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($email)
            ->setSubject('Contact form ' . Yii::$app->name)
            ->send();
    }
}
