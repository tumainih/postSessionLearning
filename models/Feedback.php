<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "feedback".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $concept_id
 * @property string|null $status
 * @property string|null $created_at
 *
 * @property Concept $concept
 * @property User $user
 */
class Feedback extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const STATUS_UNDERSTOOD = 'understood';
    const STATUS_NOT_UNDERSTOOD = 'not_understood';

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'feedback';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'concept_id', 'status'], 'required'],
            [['user_id', 'concept_id'], 'integer'],
            [['status'], 'string'],
            [['created_at'], 'safe'],
            ['status', 'in', 'range' => array_keys(self::optsStatus())],
            [['concept_id'], 'exist', 'skipOnError' => true, 'targetClass' => Concept::class, 'targetAttribute' => ['concept_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User',
            'concept_id' => 'Concept ID',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Concept]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConcept()
    {
        return $this->hasOne(Concept::class, ['id' => 'concept_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * column status ENUM value labels
     * @return string[]
     */
    public static function optsStatus()
    {
        return [
            self::STATUS_UNDERSTOOD => 'understood',
            self::STATUS_NOT_UNDERSTOOD => 'not_understood',
        ];
    }

    /**
     * @return string
     */
    public function displayStatus()
    {
        return self::optsStatus()[$this->status];
    }

    /**
     * @return bool
     */
    public function isStatusUnderstood()
    {
        return $this->status === self::STATUS_UNDERSTOOD;
    }

    public function setStatusToUnderstood()
    {
        $this->status = self::STATUS_UNDERSTOOD;
    }

    /**
     * @return bool
     */
    public function isStatusNotunderstood()
    {
        return $this->status === self::STATUS_NOT_UNDERSTOOD;
    }

    public function setStatusToNotunderstood()
    {
        $this->status = self::STATUS_NOT_UNDERSTOOD;
    }

    /**
     * Checks if the feedback status is 'understood'.
     *
     * @return bool
     */
    public function isUnderstood()
    {
        return $this->status === 'understood';
    }
}
