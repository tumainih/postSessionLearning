<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "concept".
 *
 * @property int $id
 * @property string $title
 * @property int $lecture_id
 * @property string|null $created_at
 */
class Concept extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'concept';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'lecture_id'], 'required'],
            [['lecture_id'], 'integer'],
            [['created_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Concept Title',
            'lecture_id' => 'Lecture ID',
            'created_at' => 'Created At',
        ];
    }

    public function getFeedbacks()
    {
        return $this->hasMany(\app\models\Feedback::class, ['concept_id' => 'id']);
    }
}
