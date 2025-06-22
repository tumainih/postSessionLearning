<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "module".
 *
 * @property int $id
 * @property string $name
 * @property int $course_id
 * @property int $year_of_study
 * @property string|null $created_at
 * @property int|null $created_by
 *
 * @property Course $course
 * @property Lecture[] $lectures
 */
class Module extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'module';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'course_id', 'year_of_study'], 'required'],
            [['course_id', 'year_of_study', 'created_by'], 'integer'],
            [['created_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Course::class, 'targetAttribute' => ['course_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Module Name',
            'course_id' => 'Course',
            'year_of_study' => 'Year of Study',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * Gets query for [[Course]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Course::class, ['id' => 'course_id']);
    }

    /**
     * Gets query for [[Lectures]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLectures()
    {
        return $this->hasMany(Lecture::class, ['module_id' => 'id']);
    }
} 