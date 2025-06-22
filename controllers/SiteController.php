<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\User;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->redirect(['concept/index']);
    }

    public function actionRegister()
    {
        $model = new User(['scenario' => 'register']);
        $errors = [];
        
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->email = $model->username . '@example.com'; // Auto-generate email
            $model->role = 'student';
            
            if ($model->save()) {
                Yii::$app->user->login($model);
                Yii::$app->session->setFlash('success', 'Registration successful! Welcome.');
                return $this->redirect(['concept/index']);
            } else {
                $errors = $model->getErrors();
                Yii::$app->session->setFlash('error', 'Registration failed. Please check your input.');
            }
        }

        return $this->render('register', ['model' => $model, 'errors' => $errors]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            $username = $postData['username'];
            $password = $postData['password'];
            $rememberMe = !empty($postData['rememberMe']);

            $user = User::findByUsername($username);

            if ($user && $user->validatePassword($password)) {
                Yii::$app->user->login($user, $rememberMe ? 3600*24*30 : 0);
                return $this->redirect(['concept/index']);
            } else {
                Yii::$app->session->setFlash('error', 'Incorrect username or password.');
            }
        }

        return $this->render('login');
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}
