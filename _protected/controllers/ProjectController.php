<?php

namespace app\controllers;

use app\components\ProjectLib;
use app\models\ProjectMember;
use app\models\User;
use Yii;
use app\models\Project;
use app\models\ProjectSearch;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;

/**
 * ProjectController implements the CRUD actions for Project model.
 */
class ProjectController extends AppController
{
    /**
     * 我的项目列表
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 项目详情页
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $userId = \Yii::$app->getUser()->getId();
        $projectLib = ProjectLib::getInstance();
        $pm = $projectLib->getMemberLevel($id, $userId);
        if (!$pm || $pm['level'] < ProjectMember::LEVEL_READER) {
            Yii::$app->getSession()->addFlash('warning',Yii::t('app', 'Permission Denied').' to view this project');
            return $this->goBack();
        }

        $model = $this->findModel($id);
        if (!$model) {
            return $this->redirect(['index']);
        }

        $this->initGoBackUrl();

        $projectLib = ProjectLib::getInstance();
        $leftMenu = $projectLib->getMenu($model->id);
        return $this->render('view', [
            'model' => $model,
            'leftMenu' => $leftMenu,
        ]);
    }

    /**
     * 创建项目
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $totalProjects = Project::find()->where('`user_id`=:user_id and `status`=:status', [
            ':user_id' => Yii::$app->getUser()->getId(),
            ':status' => Project::STATUS_NORMAL
        ])->count();
        if ((!Yii::$app->getUser()->can('createProject')) || ($totalProjects > 2)) {
            Yii::$app->getSession()->addFlash('warning',Yii::t('app', 'Permission Denied'));
            return $this->redirect(['index']);
        }

        $model = new Project();
        $model->user_id = \Yii::$app->getUser()->getId();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $pm = new ProjectMember();
            $pm->project_id = $model->id;
            $pm->user_id = $model->user_id;
            $pm->level = ProjectMember::LEVEL_OWNER;
            if (!$pm->save()) {
                Yii::$app->getSession()->addFlash('warning',Yii::t('app', 'Create Failed,please contact us.'));
                return $this->redirect(['/site/contact']);
            } else {
                Yii::$app->getSession()->addFlash('success',Yii::t('app', 'Create Success'));
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * 更新项目
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $id = intval($id);
        $userId = \Yii::$app->getUser()->getId();
        $projectLib = ProjectLib::getInstance();
        $pm = $projectLib->getMemberLevel($id, $userId);
        if (!$pm || $pm['level'] < ProjectMember::LEVEL_ADMIN) {
            Yii::$app->getSession()->addFlash('warning',Yii::t('app', 'Permission Denied').' to update project');
            return $this->goBack();
        }

        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->addFlash('success',Yii::t('app', 'Update Success'));
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * 删除项目
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $userId = \Yii::$app->getUser()->getId();
        $projectLib = ProjectLib::getInstance();
        $pm = $projectLib->getMemberLevel($id, $userId);
        if (!$pm || $pm['level'] < ProjectMember::LEVEL_OWNER) {
            Yii::$app->getSession()->addFlash('warning',Yii::t('app', 'Permission Denied').' to delete project');
            return $this->goBack();
        }

        $id = intval($id);
        $model = $this->findModel($id);
        if ($model) {
            $model->status = Project::STATUS_DELETED;
            $model->save();
        }

        return $this->redirect(['index']);
    }

    /**
     * @param int $id
     * @return Project
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Project::find()->where('`id`=:id and `status`=:status', [
                ':id' => $id,
                ':status' => Project::STATUS_NORMAL,
            ])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 项目文档管理
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionManage($id)
    {
        $userId = \Yii::$app->getUser()->getId();
        $projectLib = ProjectLib::getInstance();
        $pm = $projectLib->getMemberLevel($id, $userId);
        if (!$pm || $pm['level'] < ProjectMember::LEVEL_EDITOR) {
            Yii::$app->getSession()->addFlash('warning',Yii::t('app', 'Permission Denied').' to manage catalog docs');
            return $this->goBack();
        }

        $model = $this->findModel($id);
        if (!$model) {
            return $this->redirect(['index']);
        }

        $this->initGoBackUrl();

        $projectLib = ProjectLib::getInstance();
        $leftMenu = $projectLib->getMenu($model->id);
        $docList = $projectLib->getDocList($model->id);
        return $this->render('manage', [
            'model' => $model,
            'leftMenu' => $leftMenu,
            'docList' => $docList,
        ]);
    }

    /**
     * 项目成员管理
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionMember($id)
    {
        $userId = \Yii::$app->getUser()->getId();
        $projectLib = ProjectLib::getInstance();
        $pm = $projectLib->getMemberLevel($id, $userId);
        if (!$pm || $pm['level'] < ProjectMember::LEVEL_EDITOR) {
            Yii::$app->getSession()->addFlash('warning',Yii::t('app', 'Permission Denied').' to manage member');
            return $this->goBack();
        }

        $model = $this->findModel($id);
        if (!$model) {
            return $this->redirect(['index']);
        }

        $this->initGoBackUrl();

        $projectLib = ProjectLib::getInstance();
        $leftMenu = $projectLib->getMenu($id);
        $memberList = $projectLib->getMemberList($id);
        $dataProvider = new ArrayDataProvider();
        $dataProvider->setModels($memberList);
        return $this->render('member', [
            'model' => $model,
            'leftMenu' => $leftMenu,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 添加项目成员
     * @return \yii\web\Response
     */
    public function actionAddMember()
    {
        $projectId = intval(\Yii::$app->getRequest()->post('id',0));
        $userId = Yii::$app->getUser()->getId();
        $pm = ProjectLib::getInstance()->getMemberLevel($projectId, $userId);
        if (!$pm || $pm['level'] < ProjectMember::LEVEL_EDITOR) {
            Yii::$app->getSession()->addFlash('error',Yii::t('app','Permission Denied').' to add member');
            return $this->goBack();
        }
        $username = trim(\Yii::$app->getRequest()->post('username',''));
        $member = User::findByUsername($username);
        if (!$member) {
            Yii::$app->getSession()->addFlash('error',Yii::t('app','user not found by username:'.$username));
            return $this->goBack();
        }

        $cnt = ProjectMember::find()->where('`project_id`=:project_id and `user_id`=:user_id',[
            ':project_id' => $projectId,
            ':user_id' => $member->id
        ])->count();
        if ($cnt) {
            Yii::$app->getSession()->addFlash('error',Yii::t('app','project member already exist with username:'.$username));
            return $this->goBack();
        }

        $pm = new ProjectMember();
        $pm->project_id = $projectId;
        $pm->user_id = $member->id;
        $pm->level = intval(Yii::$app->getRequest()->post('level',ProjectMember::LEVEL_READER));
        if ($pm->save()) {
            //cache reset logic
            Yii::$app->getCache()->delete("Project:MemberIds:$projectId");
            Yii::$app->getCache()->delete("Project:MemberList:$projectId");
            Yii::$app->getCache()->delete("Project:MemberLevel:$projectId:{$pm->user_id}");
            
            Yii::$app->getSession()->addFlash('success',Yii::t('app','Member add success.'));
        } else {
            Yii::$app->getSession()->addFlash('error',Yii::t('app','Member add failed,please contact us.'));
        }

        return $this->goBack();
    }

    /**
     * 设置项目成员级别
     * @param $id
     * @param $user_id
     * @param $level
     * @return \yii\web\Response
     */
    public function actionSetMemberLevel($id,$user_id,$level)
    {
        $projectId = intval(\Yii::$app->getRequest()->post('id',0));
        $userId = Yii::$app->getUser()->getId();
        $pm = ProjectLib::getInstance()->getMemberLevel($projectId, $userId);
        if (!$pm || $pm['level'] < ProjectMember::LEVEL_EDITOR) {
            Yii::$app->getSession()->addFlash('error',Yii::t('app','Permission Denied').' to add member');
            return $this->goBack();
        }
        if (Yii::$app->getRequest()->getIsPost()) {
            if (isset(ProjectMember::$levelList[$level])) {
                if(ProjectMember::updateAll(['level' => intval($level)],[
                    'project_id' => $id,
                    'user_id' => $user_id,
                ])) {
                    //cache reset logic
                    Yii::$app->getCache()->delete("Project:MemberList:$id");
                    Yii::$app->getCache()->delete("Project:MemberLevel:$id:$user_id");
                    
                    Yii::$app->getSession()->addFlash('success',Yii::t('app','Update Success'));
                    
                    return $this->goBack();
                }
            }
        } 
        return $this->goHome();
    }

    /**
     * 移除项目成员
     * @param $id
     * @param $user_id
     * @return \yii\web\Response
     */
    public function actionDelMember($id,$user_id)
    {
        $projectId = intval(\Yii::$app->getRequest()->post('id',0));
        $userId = Yii::$app->getUser()->getId();
        $pm = ProjectLib::getInstance()->getMemberLevel($projectId, $userId);
        if (!$pm || $pm['level'] < ProjectMember::LEVEL_EDITOR) {
            Yii::$app->getSession()->addFlash('error',Yii::t('app','Permission Denied').' to add member');
            return $this->goBack();
        }
        if (Yii::$app->getRequest()->getIsPost()) {
            if(ProjectMember::deleteAll([
                'project_id' => $id,
                'user_id' => $user_id,
            ])) {
                //cache reset logic
                Yii::$app->getCache()->delete("Project:MemberIds:$id");
                Yii::$app->getCache()->delete("Project:MemberList:$id");
                Yii::$app->getCache()->delete("Project:MemberLevel:$id:$user_id");
                
                Yii::$app->getSession()->addFlash('success',Yii::t('app','Delete Success'));
                
                return $this->goBack();
            }
        }
        return $this->goHome();
    }

    
}
