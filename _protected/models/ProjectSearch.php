<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Project;
use yii\helpers\ArrayHelper;

/**
 * ProjectSearch represents the model behind the search form about `app\models\Project`.
 */
class ProjectSearch extends Project
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['name', 'description'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Project::find()->where('`status`=' . self::STATUS_NORMAL);

        if (\Yii::$app->getUser()->getIsGuest()) {
            $query->andWhere(['user_id'=>0]);
        } else {
            $userId = \Yii::$app->getUser()->getId();
            $userProjectIds = ProjectMember::find()
                ->select('project_id')
                ->where('user_id=:uid',[':uid'=>$userId])
                ->asArray()
                ->all();
            if ($userProjectIds) {
                $userProjectIds = ArrayHelper::getColumn($userProjectIds, 'project_id');
                $query->andWhere('`user_id` = :uid or `id` in ('.implode(',',$userProjectIds).')', [
                    ':uid' => $userId
                ]);
            } else {
                $query->andWhere('`user_id` = :uid', [
                    ':uid' => $userId
                ]);
            }
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
