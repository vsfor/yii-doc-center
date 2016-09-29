<?php
namespace app\components;

use app\models\OauthWx;
use Yii;
use yii\base\Component;

class OauthLib extends Component
{
    public function wxCodeUrl($userId=0)
    {
        $url = 'https://open.weixin.qq.com/connect/qrconnect?';
        $params['appid'] = 'wxf45f89b87d87eaa1';
        $params['redirect_uri'] = 'http://ydc.jeen.wang/oauth/wx';
        $params['response_type'] = 'code';
        $params['scope'] = 'snsapi_login';
        $params['state'] = $this->getStateStr($userId);
        return $url . http_build_query($params);
    }

    public function getStateStr($userId=0)
    {
        $salt = mt_rand(1000,9999).date("yHismd");
        return md5("wx:$userId:$salt").":$salt:$userId";
    }

    public function checkWxState($get)
    {
        if (isset($get['code'], $get['state'])) {
            $ta = explode(':', $get['state']);
            if (count($ta) == 3 && $ta[0] == md5("wx:{$ta[2]}:{$ta[1]}")) {
                if(Yii::$app->getUser()->getIsGuest()) {
                    return 0;
                } else {
                    return $ta[2] == Yii::$app->getUser()->id ? $ta[2] : 0;
                }
            }
        }
        return null;
    }

    public function wxAccessToken($code)
    {
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?';
        $url .= 'appid='.Yii::$app->params['oAuth']['wx']['appId'];
        $url .= '&secret='.Yii::$app->params['oAuth']['wx']['appSecret'];
        $url .= '&code='.$code.'&grant_type=authorization_code';
        $res = file_get_contents($url);
        $ret = json_decode($res, true);
        if (isset($ret['openid'],$ret['unionid'])) {
            return $ret;
        }
        return false;
    }

    public function wxRefreshToken()
    {

    }

    public function wxAuthCheck($accessToken,$openId)
    {
        $url = "https://api.weixin.qq.com/sns/auth?access_token=$accessToken&openid=$openId";
        $res = file_get_contents($url);
        $ret = json_decode($res, true);
        if (isset($ret['errcode'], $ret['errmsg'])
            && $ret['errcode'] == 0
            && $ret['errmsg'] == 'ok') {
            return true;
        }
        return false;
    }

    public function wxUserInfo($accessToken,$openId)
    {
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=$accessToken&openid=$openId";
        $res = file_get_contents($url);
        $ret = json_decode($res, true);
        if (isset($ret['openid'], $ret['unionid'])) {
            return $ret;
        }
        return false;
    }

    public function wxGetRows($openId='',$unionId='')
    {
        if ($openId || $unionId) {
            $query = OauthWx::find()->where('`user_id`!=0 and `status`='.OauthWx::STATUS_NORMAL);
            if ($unionId) $query->andWhere('`unionid`=:unionid',[':unionid'=>$unionId]);
            elseif ($openId) $query->andWhere('`openid`=:openid',[':openid'=>$openId]);
            return $query->asArray()->all();
        }
        return [];
    }

    public function wxGetEmptyRow($openId='',$unionId='')
    {
        if ($openId || $unionId) {
            $query = OauthWx::find()->where('`user_id`=0 and `status`='.OauthWx::STATUS_NORMAL);
            if ($unionId) $query->andWhere('`unionid`=:unionid',[':unionid'=>$unionId]);
            elseif ($openId) $query->andWhere('`openid`=:openid',[':openid'=>$openId]);
            return $query->one();
        }
        return false;
    }
    
    public function wxCheckExist($userId,$openId='',$unionId='')
    {
        if ($openId || $unionId) {
            $query = OauthWx::find()->where('`user_id`=:user_id', [':user_id'=>$userId]);
            if ($unionId) $query->andWhere('`unionid`=:unionid',[':unionid'=>$unionId]);
            elseif ($openId) $query->andWhere('`openid`=:openid',[':openid'=>$openId]);
            return $query->one();
        }
        return false;
    }

    public function userWxRows($userId)
    {
        return OauthWx::find()->where('`user_id`=:user_id and `status`='.OauthWx::STATUS_NORMAL, [':user_id'=>$userId])->asArray()->all();
    }
    
    
}