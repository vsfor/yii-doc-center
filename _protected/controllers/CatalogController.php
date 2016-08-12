<?php

namespace app\controllers;

use app\components\ProjectLib;
use app\models\ProjectMember;
use Yii;
use app\models\Catalog;
use app\models\CatalogSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CatalogController implements the CRUD actions for Catalog model.
 */
class CatalogController extends AppController
{
    /**
     * 添加项目目录
     * @param $project_id
     * @return string|\yii\web\Response
     */
    public function actionCreate($project_id)
    {
        $project_id = intval($project_id);
        $userId = \Yii::$app->getUser()->getId();
        $projectLib = ProjectLib::getInstance();
        $pm = $projectLib->getMemberLevel($project_id, $userId);
        if (!$pm || $pm['level'] < ProjectMember::LEVEL_EDITOR) {
            Yii::$app->getSession()->addFlash('warning',Yii::t('app', 'Permission Denied').' to create catalog');
            return $this->goBack();
        }
        $model = new Catalog();
        $model->project_id = $project_id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //cache reset logic
            Yii::$app->getCache()->delete("Project:Menu:$project_id");
            Yii::$app->getCache()->delete("Project:Catalog:$project_id");
            Yii::$app->getCache()->delete("Project:DocList:$project_id");

            Yii::$app->getSession()->addFlash('success',Yii::t('app', 'Create Success'));

            return $this->goBack();
        }
        $leftMenu = $projectLib->getMenu($project_id);
        $parents = $projectLib->getCatOptions($project_id,0,2,1);
        return $this->render('create', [
            'model' => $model,
            'leftMenu' => $leftMenu,
            'parents' => $parents,
        ]);
    }

    /**
     * 更新项目目录信息
     * @param $id
     * @param $project_id
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
            Yii::$app->getSession()->addFlash('warning',Yii::t('app', 'Permission Denied').' to update catalog');
            return $this->redirect(['/site/index']);
        }
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //cache reset logic
            Yii::$app->getCache()->delete("Project:Menu:$project_id");
            Yii::$app->getCache()->delete("Project:Catalog:$project_id");
            Yii::$app->getCache()->delete("Project:DocList:$project_id");

            Yii::$app->getSession()->addFlash('success',Yii::t('app', 'Update Success'));

            return $this->goBack();
        }

        $leftMenu = $projectLib->getMenu($project_id);
        $parents = $projectLib->getSubCatOptions($project_id);
        return $this->render('update', [
            'model' => $model,
            'leftMenu' => $leftMenu,
            'parents' => $parents,
        ]);
    }

    /**
     * 移除项目目录
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
            Yii::$app->getSession()->addFlash('warning',Yii::t('app', 'Permission Denied').' to delete dialog');
            return $this->goBack();
        }

        $model = $this->findModel($id);
        if ($model) {
            $model->status = Catalog::STATUS_DELETED;
            $model->save();
        }
        //cache reset logic
        Yii::$app->getCache()->delete("Project:Menu:$project_id");
        Yii::$app->getCache()->delete("Project:Catalog:$project_id");
        Yii::$app->getCache()->delete("Project:DocList:$project_id");

        return $this->goBack();
    }

    /**
     * @param $id
     * @return Catalog
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Catalog::find()->where('`id`=:id and `status`=:status',[
                ':id' => intval($id),
                ':status' => Catalog::STATUS_NORMAL,
        ])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
