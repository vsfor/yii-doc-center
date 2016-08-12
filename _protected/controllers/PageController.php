<?php

namespace app\controllers;

use app\components\ProjectLib;
use app\models\PageHistory;
use app\models\ProjectMember;
use Yii;
use app\models\Page;
use yii\web\NotFoundHttpException;

/**
 * PageController implements the CRUD actions for Page model.
 */
class PageController extends AppController
{
    /**
     * useful
     * @param int $id
     * @param int $project_id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionView($id, $project_id)
    {
        $project_id = intval($project_id);
        $userId = \Yii::$app->getUser()->getId();
        $projectLib = ProjectLib::getInstance();
        $pm = $projectLib->getMemberLevel($project_id, $userId);
        if (!$pm || $pm['level'] < ProjectMember::LEVEL_READER) {
            Yii::$app->getSession()->addFlash('warning',Yii::t('app', 'Permission Denied').' to view this page');
            return $this->goBack();
        }

        $this->initGoBackUrl();

        $leftMenu = $projectLib->getMenu($project_id);
        $historyList = $projectLib->getPageHistoryList($id);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'leftMenu' => $leftMenu,
            'historyList' => $historyList,
        ]);
    }

    /**
     * useful
     * @param int $project_id
     * @return string|\yii\web\Response
     */
    public function actionCreate($project_id)
    {
        $project_id = intval($project_id);
        $userId = \Yii::$app->getUser()->getId();
        $projectLib = ProjectLib::getInstance();
        $pm = $projectLib->getMemberLevel($project_id, $userId);
        if (!$pm || $pm['level'] < ProjectMember::LEVEL_EDITOR) {
            Yii::$app->getSession()->addFlash('warning',Yii::t('app', 'Permission Denied').' to create page');
            return $this->goBack();
        }
        $model = new Page();
        $model->project_id = $project_id;
        $model->author_id = $userId;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //cache reset logic
            Yii::$app->getCache()->delete("Project:Menu:$project_id");
            Yii::$app->getCache()->delete("Project:Catalog:$project_id");
            Yii::$app->getCache()->delete("Project:DocList:$project_id");

            Yii::$app->getSession()->addFlash('success',Yii::t('app', 'Create Success'));
            return $this->redirect(['view', 'id' => $model->id, 'project_id' => $project_id]);
        }
        $leftMenu = $projectLib->getMenu($project_id);
        $catalogs = $projectLib->getCatOptions($project_id);
        return $this->render('create', [
            'model' => $model,
            'leftMenu' => $leftMenu,
            'catalogs' => $catalogs,
        ]);
    }

    /**
     * useful
     * @param int $id
     * @param int $project_id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id, $project_id)
    {
        $project_id = intval($project_id);
        $userId = \Yii::$app->getUser()->getId();
        $projectLib = ProjectLib::getInstance();
        $pm = $projectLib->getMemberLevel($project_id, $userId);
        if (!$pm || $pm['level'] < ProjectMember::LEVEL_EDITOR) {
            Yii::$app->getSession()->addFlash('warning',Yii::t('app', 'Permission Denied').' to update page');
            return $this->goBack();
        }
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $historyAttrs = $model->getOldAttributes();
            $model->author_id = $userId;
            if ($model->save()) {
                //仅当文档内容有变更时,保存历史版本
                if ($model->content != $historyAttrs['content']) {
                    $history = new PageHistory();
                    $history->page_id = $historyAttrs['id'];
                    unset($historyAttrs['id']);
                    $history->setAttributes($historyAttrs);
                    $history->save();
                }
                //cache reset logic
                Yii::$app->getCache()->delete("Project:Menu:$project_id");
                Yii::$app->getCache()->delete("Project:Catalog:$project_id");
                Yii::$app->getCache()->delete("Project:DocList:$project_id");

                Yii::$app->getSession()->addFlash('success',Yii::t('app', 'Update Success'));
                return $this->redirect(['view', 'id' => $model->id, 'project_id' => $project_id]);
            }
        }
        $leftMenu = $projectLib->getMenu($project_id);
        $catalogs = $projectLib->getCatOptions($project_id);
        return $this->render('update', [
            'model' => $model,
            'leftMenu' => $leftMenu,
            'catalogs' => $catalogs,
        ]);
    }

    /**
     * 删除项目文档
     * @param $id
     * @param $project_id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id, $project_id)
    {
        $project_id = intval($project_id);
        $userId = \Yii::$app->getUser()->getId();
        $projectLib = ProjectLib::getInstance();
        $pm = $projectLib->getMemberLevel($project_id, $userId);
        if (!$pm || $pm['level'] < ProjectMember::LEVEL_ADMIN) {
            Yii::$app->getSession()->addFlash('warning',Yii::t('app', 'Permission Denied').' to delete page');
            return $this->goBack();
        }
        $model = $this->findModel($id);
        if ($model && $model->project_id == $project_id) {
            $model->status = Page::STATUS_DELETED;
            $model->save();
        }
        //cache reset logic
        Yii::$app->getCache()->delete("Project:Menu:$project_id");
        Yii::$app->getCache()->delete("Project:Catalog:$project_id");
        Yii::$app->getCache()->delete("Project:DocList:$project_id");
        
        return $this->goBack();
    }

    /**
     * useful
     * @param int $id
     * @return Page
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Page::find()->where('`id`=:id and `status`=:status', [
                ':id' => $id,
                ':status' => Page::STATUS_NORMAL
            ])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /** useful */
    public function actionGethistory($id)
    {
        $ret = [
            'time' => '',
            'title' => 'Not Found!',
            'description' => '',
            'content' => '',
        ];
        $history = PageHistory::findOne($id);
        if ($history) {
            $ret['time'] = date("Y-m-d H:i:s", $history->updated_at);
            $ret['title'] = $history->title;
            $ret['description'] = $history->description;
            $ret['content'] = $history->content;
        }
        return json_encode($ret);
    }

}
