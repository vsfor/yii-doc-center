<?php

namespace app\controllers;

use app\components\TemplateLib;
use Yii;
use app\models\Template;
use app\models\TemplateSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TemplateController implements the CRUD actions for Template model.
 */
class TemplateController extends ControllerBase
{
    /**
     * 获取模板内容
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionGetContent($id)
    {
        if (in_array($id, ['api','table'])) {
            return (new TemplateLib())->getSystemTemplateContent($id);
        }
        $model = $this->findModel($id);
        if ($model && $model->content) {
            return $model->content;
        } else {
            return '';
        }
    }

    /**
     * 添加页面内容为模板
     * @return string
     */
    public function actionAddPage()
    {
        $title = Yii::$app->getRequest()->post('title', '');
        $content = Yii::$app->getRequest()->post('content', '');
        if ($title && $content) {
            $template = new Template();
            $template->author_id = Yii::$app->getUser()->getId();
            $template->title = strval($title);
            $template->content = strval($content);
            if ($template->save()) {
                return 'success';
            }
        }
        return 'error';
    }
    
    /**
     * Finds the Template model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Template the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Template::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
