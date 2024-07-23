<?php
namespace app\controllers;

use app\components\Jeen;
use app\models\User;
use app\models\UserSearch;
use app\rbac\helpers\RbacHelper;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use Yii;
use yii\web\UploadedFile;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends ControllerBase
{
    /**
     * How many users we want to display per page.
     * @var int
     */
    protected $_pageSize = 11;

    /**
     * 用户列表管理
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $this->_pageSize);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 查看用户详情
     *
     * @param  integer $id The user id.
     * @return string
     *
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render('view', ['model' => $this->findModel($id)]);
    }

    /**
     * 添加用户
     *
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $user = new User(['scenario' => 'create']);

        if (!$user->load(Yii::$app->request->post())) {
            return $this->render('create', ['user' => $user]);
        }

        $user->setPassword($user->password);
        $user->generateAuthKey();

        if (!$user->save()) {
            return $this->render('create', ['user' => $user]);
        }
 
        if (!RbacHelper::assignRole($user->id)) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'There was some error while saving user role.'));
        }

        return $this->redirect('index');
    }

    /**
     * 编辑用户信息
     *
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param  integer $id The user id.
     * @return string|\yii\web\Response
     *
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        // load user data
        $user = $this->findModel($id);

        if (!$user->load(Yii::$app->request->post())) {
            return $this->render('update', ['user' => $user]);
        }

        // only if user entered new password we want to hash and save it
        if ($user->password) {
            $user->setPassword($user->password);
        }

        // if admin is activating user manually we want to remove account activation token
        if ($user->status == User::STATUS_ACTIVE && $user->account_activation_token != null) {
            $user->removeAccountActivationToken();
        }         

        if (!$user->save()) {
            return $this->render('update', ['user' => $user]);
        }

        $roles = Yii::$app->getAuthManager()->getRolesByUser($user->id);
        if (!$roles && !RbacHelper::assignRole($user->id)) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'There was some error while saving user role.'));
        }

        return $this->redirect(['view', 'id' => $user->id]);
    }

    /**
     * 删除用户
     *
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws \Exception
     */
    public function actionDelete($id)
    {
        $user = $this->findModel($id);
        if (!$user) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'There was some error while deleting user role.'));
            return $this->redirect(['index']);
        }

        $user->status = $user::STATUS_DELETED;
        // delete user or throw exception if could not
        if (!$user->save()) {
            throw new ServerErrorHttpException(Yii::t('app', 'We could not delete this user.'));
        }

        $auth = Yii::$app->authManager;
        $info = true; // monitor info status

        // get user role if he has one  
        if ($roles = $auth->getRolesByUser($id)) {
            // it's enough for us the get first assigned role name
            $role = array_keys($roles)[0]; 
        }

        // remove role if user had it
        if (isset($role)) {
            $info = $auth->revoke($auth->getRole($role), $id);
        }

        if (!$info) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'There was some error while deleting user role.'));
            return $this->redirect(['index']);
        }

        Yii::$app->session->setFlash('success', Yii::t('app', 'You have successfuly deleted user and his role.'));
        
        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param  integer $id The user id.
     * @return User The loaded model.
     *
     * @throws NotFoundHttpException if the model cannot be found.
     */
    protected function findModel($id)
    {
        $model = User::findOne($id);

        if (is_null($model)) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        } 

        return $model;
    }

    /**
     * 查看个人信息
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionProfile()
    {
        $this->layout = 'fullPage.php';
        $id = \Yii::$app->getUser()->getId();
        return $this->render('profile', ['model' => $this->findModel($id)]);
    }

    /**
     * 编辑个人信息
     *
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdateProfile()
    {
        $this->layout = 'fullPage.php';
        $id = \Yii::$app->getUser()->getId();

        if ($id == 1001) {//demo userId
            Yii::$app->session->setFlash('error', Yii::t('app', 'Test User Update Not Allow'));

            return $this->redirect(['profile', 'id' => $id]);
        }

        // load user data
        $user = $this->findModel($id);

        if (!$user->load(Yii::$app->request->post())) {
            return $this->render('update-profile', ['user' => $user]);
        }

        // only if user entered new password we want to hash and save it
        if ($user->password) {
            $user->setPassword($user->password);
        }

        // if admin is activating user manually we want to remove account activation token
        if ($user->status == User::STATUS_ACTIVE && $user->account_activation_token != null) {
            $user->removeAccountActivationToken();
        }

        if (!$user->save()) {
            return $this->render('update-profile', ['user' => $user]);
        }

        Yii::$app->session->setFlash('success', Yii::t('app', 'Update Success'));

        return $this->redirect(['profile', 'id' => $user->id]);
    }

    /**
     * 上传图片
     *
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function actionUploadImage()
    { //注意:用于上传图片的editor.md/plugins/image-dialog/image-dialog.js 有结合  yii _csrf 验证作部分修改
        $ret = [
            'success' => 0,
            'url' => '',
            'message' => Yii::t('app','Some error occurred, please contact us.'),
        ];
        $userId = Yii::$app->getUser()->getId();
        $image = UploadedFile::getInstanceByName('editormd-image-file');
        if ($image->error) {
            return json_encode($ret);
        }
        if ($image->size > 2048000 || !in_array(strtolower($image->type),[
            'image/png','image/jpg','image/jpeg','image/webp','image/bmp','image/gif'
        ])) {
            $ret['message'] = 'only support png,jpg,jpeg,webp,bmp,gif,ico under maxsize 2M';
            return json_encode($ret);
        }
        $saveName = "/uploads/$userId/".date("Y/m/d/His.").$image->getExtension();
        $savePath = WEB_PATH . $saveName;
        $saveDir =  dirname($savePath);
        if (FileHelper::createDirectory($saveDir, 0777)) {
            if ($image->saveAs($savePath)) {
                $ret['success'] = 1;
                $ret['url'] = Url::to("@web$saveName",true);
                $ret['message'] = 'ok';
            }
        }
        return json_encode($ret);
    }

    /**
     * 内测资格邮件
     * @return void
     */
    public function actionBe()
    {
        Yii::$app->urlManager->setScriptUrl("http://ydc.jeen.wang/");
        /** @var $user User */
        $user = User::find()->where('`id`=:id',[
            ':id'=>123//1003
        ])->one();
        if (!$user) {
            Jeen::echoln('User Not Found');
            exit();
        }
        $ret = Yii::$app->mailer->compose('betaUserTip', ['user' => $user])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($user->email)
            ->setSubject('Beta Usage Tip For ' . Yii::$app->name)
            ->send();
        Jeen::echoln($ret);
        exit();
    }
}
