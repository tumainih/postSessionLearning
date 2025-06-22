<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\Concept;
use app\models\Feedback;

class PostsessionController extends Controller
{
    // Show all concepts and feedback buttons
    public function actionIndex()
    {
        $concepts = Concept::find()->orderBy(['id' => SORT_DESC])->all();
        return $this->render('index', ['concepts' => $concepts]);
    }

    // Add a new concept (admin only)
    public function actionCreate()
    {
        $model = new Concept();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', ['model' => $model]);
    }

    // Handle student vote (green/red)
    public function actionVote($id, $status)
    {
        $model = new Feedback();
        $model->concept_id = $id;
        $model->status = $status;
        $model->student_name = Yii::$app->request->post('name');
        $model->contact = Yii::$app->request->post('contact');

        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Feedback submitted successfully.');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to submit feedback.');
        }

        return $this->redirect(['index']);
    }

    // View who understood the concept (green)
    public function actionViewUnderstood($id)
    {
        $feedbacks = Feedback::find()
            ->where(['concept_id' => $id, 'status' => 'understood'])
            ->all();

        if (empty($feedbacks)) {
            throw new NotFoundHttpException('No students marked this concept as understood.');
        }

        return $this->render('understood', ['list' => $feedbacks]);
    }
}
