<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\User;

class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->isAdmin();
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'set-role' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $users = User::find()->orderBy(['id' => SORT_ASC])->all();
        return $this->render('index', ['users' => $users]);
    }

    public function actionSetRole($id, $role)
    {
        $user = User::findOne($id);
        if ($user) {
            $user->role = $role;
            if ($user->save()) {
                Yii::$app->session->setFlash('success', "User role has been updated.");
            } else {
                Yii::$app->session->setFlash('error', "Failed to update user role.");
            }
        }
        return $this->redirect(['index']);
    }
}
