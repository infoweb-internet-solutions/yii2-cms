<?php

namespace infoweb\cms\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;;
use yii\helpers\StringHelper;

/**
 * SearchSlider represents the model behind the search form about `app\models\Slider`.
 */
class ImageSearch extends Image
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            //[['name'], 'safe'],
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
    public function search($params, $id, $className = null)
    {
        $query = Image::find()->where(['itemId' => $id]);

        if ($className) {
            $query->andWhere([
                'modelName' => StringHelper::basename($className),
            ]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => ['defaultOrder' => ['position' => Yii::$app->getModule('cms')->imagesSorting ]]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'name' => $this->name,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
