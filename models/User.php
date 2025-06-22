<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public $password; // Virtual property for registration form
    
    public static function tableName()
    {
        return '{{%user}}';
    }

    public function rules()
    {
        return [
            [['username', 'email', 'role'], 'required'],
            [['username', 'email'], 'string', 'max' => 255],

            // Add username format validation (no spaces)
            ['username', 'match', 'pattern' => '/^[a-zA-Z0-9_]+$/', 'message' => 'Username can only contain letters, numbers, and underscores.'],
            
            // Add email format validation
            ['email', 'email'],

            [['username', 'email'], 'unique'],
            [['role'], 'string', 'max' => 50],
            [['contact_number'], 'string', 'max' => 20],
            [['auth_key'], 'string', 'max' => 32],
            [['access_token', 'password_reset_token'], 'string', 'max' => 255],
            [['status'], 'integer'],
            [['created_at'], 'safe'],
            [['role'], 'in', 'range' => ['admin', 'student', 'teacher']],
            // Password validation for registration
            [['password'], 'string', 'min' => 6, 'on' => 'register'],
            [['password'], 'required', 'on' => 'register'],
            // Password hash is generated automatically, not required
            [['password_hash'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'password_hash' => 'Password Hash',
            'role' => 'Role',
            'contact_number' => 'Contact Number',
            'auth_key' => 'Auth Key',
            'access_token' => 'Access Token',
            'password_reset_token' => 'Password Reset Token',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => 10]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token, 'status' => 10]);
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => 10]);
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isStudent()
    {
        return $this->role === 'student';
    }

    public function isTeacher()
    {
        return $this->role === 'teacher';
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->auth_key = Yii::$app->security->generateRandomString();
                $this->status = 10; // Active status
            }
            
            // Hash password if it's set
            if (!empty($this->password)) {
                $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
            }
            
            return true;
        }
        return false;
    }
}
