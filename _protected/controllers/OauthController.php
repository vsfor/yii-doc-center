<?php
namespace app\controllers;

use app\components\Jeen;
use app\models\OauthWx;
use app\models\SignupForm;
use app\components\OauthLib;
use app\models\User;
use app\models\UserIdentity;
use yii\helpers\Json;
use yii\web\Controller;
use Yii;

/**
 * Site controller.
 * It is responsible for displaying static pages, logging users in and out,
 * sign up and account activation, and password reset.
 */
class OauthController extends Controller
{
    public $layout = false;

 
    /**
     * 接口测试首页
     * 
     * @return string
     */
    public function actionWx()
    {
        $this->layout = false;
        $lib = new OauthLib();
        $stateResult = $lib->checkWxState($_GET);
        if (is_null($stateResult)) {
            return $this->redirect(['/site/signup']);
        }

        $wxInfo = $lib->wxAccessToken(trim($_GET['code']));
        if ($wxInfo) {
            if ($stateResult) { //关联账号
                /** @var \app\models\OauthWx $exist */
                $exist = $lib->wxCheckExist($stateResult, $wxInfo['openid'], $wxInfo['unionid']);
                if ($exist) { //已绑定相同账号
                    if ($exist->status != OauthWx::STATUS_NORMAL) {
                        $exist->status = OauthWx::STATUS_NORMAL;
                        $exist->save();
                    }
                    Yii::$app->getSession()->setFlash('success','微信账号绑定成功.');
                    return $this->goHome();
                }

                /** @var \app\models\OauthWx $row */
                $row = $lib->wxGetEmptyRow($wxInfo['openid'], $wxInfo['unionid']);
                if ($row) { //存在空数据则直接关联
                    $row->user_id = intval($stateResult);
                    $row->access_token = $wxInfo['access_token'];
                    $row->expire_time = time() + $wxInfo['expires_in'];
                    $row->refresh_token = $wxInfo['refresh_token'];
                    $row->openid = $wxInfo['openid'];
                    if (time() - $row->info_time > 604800) { //超过一周,刷新微信用户信息
                        $row->info_data = Json::encode($lib->wxUserInfo($wxInfo['access_token'], $wxInfo['openid']));
                        $row->info_time = time();
                    }
                } else { //不存在则创建保存
                    $row = new OauthWx();
                    $row->user_id = intval($stateResult);
                    $row->access_token = $wxInfo['access_token'];
                    $row->expire_time = time() + $wxInfo['expires_in'];
                    $row->refresh_token = $wxInfo['refresh_token'];
                    $row->openid = $wxInfo['openid'];
                    $row->unionid = $wxInfo['unionid'];
                    $row->info_time = time();
                    $row->info_data = Json::encode($lib->wxUserInfo($wxInfo['access_token'], $wxInfo['openid']));
                    $row->status = 1;
                }
                if ($row->save()) {
                    Yii::$app->getSession()->setFlash('success','成功关联微信账号.');
                } else {
                    Yii::warning('wx oauth save error'.Json::encode($row->getErrors()),'oauthWx');
                    Yii::$app->getSession()->setFlash('warning','关联微信账号异常,请联系管理员.');
                }
            } else { //尝试登录
                $rows = $lib->wxGetRows($wxInfo['openid'], $wxInfo['unionid']);
                if (!$rows) {
                    if (!$lib->wxGetEmptyRow($wxInfo['openid'], $wxInfo['unionid'])) {
                        $row = new OauthWx();
                        $row->user_id = 0;
                        $row->access_token = $wxInfo['access_token'];
                        $row->expire_time = time() + $wxInfo['expires_in'];
                        $row->refresh_token = $wxInfo['refresh_token'];
                        $row->openid = $wxInfo['openid'];
                        $row->unionid = $wxInfo['unionid'];
                        $row->info_time = time();
                        $row->info_data = Json::encode($lib->wxUserInfo($wxInfo['access_token'], $wxInfo['openid']));
                        $row->status = 1;
                        if (!$row->save()) {
                            Yii::warning('wx oauth save error'.Json::encode($row->getErrors()),'oauthWx');
                        }
                    }
                    Yii::$app->getSession()->setFlash('error','尚未检测到关联账号,请登录或注册后完成关联.');
                    return $this->redirect(['/site/login']);
                } elseif (count($rows) == 1) {
                    $userIdentity = UserIdentity::findIdentity($rows[0]['user_id']);
                    if ($userIdentity) {
                        Yii::$app->getUser()->login($userIdentity, 604800);
                        Yii::$app->getSession()->setFlash('success','欢迎回来.');
                    } else {
                        Yii::$app->getSession()->setFlash('warning','您的账号状态异常,请确认是否激活或联系管理员.');
                    }
                } else {
                    //todo
                    Yii::$app->getSession()->setFlash('success','您绑定了很多账号.');
                    $userModels = [];
                    foreach ($rows as $row) {
                        $userModels[] = UserIdentity::findIdentity($row['user_id'],true);
                    }
                    $this->layout = 'fullPage.php';
                    return $this->render('chose_login_user',[
                        'users' => $userModels
                    ]);
                }
            }
        } else {
            Yii::warning('wx oauth wxAccessToken error'.Json::encode($_GET),'oauthWx');
            Yii::$app->getSession()->setFlash('error','微信请求失败,请重试或联系管理员.');
        }

        return $this->goHome();
    }


    public function actionUnbind()
    {
        $ret = [
            'code' => '0',
            'msg' => 'error'
        ];
        if (Yii::$app->getRequest()->getIsAjax() && Yii::$app->getRequest()->getIsPost()) {
            if (!Yii::$app->getUser()->getIsGuest()) {
                if (isset($_GET['type'],$_GET['id'])) {
                    if ($_GET['type'] == 'wx') {
                        $num = OauthWx::updateAll([
                            'status' => OauthWx::STATUS_DELETE,
                            'updated_at' => time()
                        ],'`id`=:id and `user_id`=:user_id',[
                            ':id' => intval($_GET['id']),
                            ':user_id' => Yii::$app->getUser()->id
                        ]);
                        if (is_numeric($num)) {
                            $ret = [
                                'code' => 1,
                                'msg' => 'ok'
                            ];
                        } else {
                            $ret['msg'] = '解绑失败,请联系管理员';
                        }
                    }
                }
            }
        }
        return json_encode($ret);
    }


    public function actionLogin($id=0)
    {
        if (Yii::$app->getRequest()->getIsPost()) {
            $userIdentity = UserIdentity::findIdentity(intval($id));
            if ($userIdentity) {
                Yii::$app->getUser()->login($userIdentity, 604800);
                Yii::$app->getSession()->setFlash('success','欢迎回来.');
            } else {
                Yii::$app->getSession()->setFlash('error','账号异常,登录失败.');
            }
        }
        return $this->goHome();
    }

}
