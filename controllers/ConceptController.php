<?php

namespace app\controllers;

use app\models\Feedback;
use app\models\Concept;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use Yii;
use yii\helpers\ArrayHelper;

class ConceptController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'logout' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'index', 'create', 'dashboard', 'submit-feedback', 'remove-feedback',
                    'view-feedback', 'my-feedback', 'my-report', 'download-report'
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionDashboard()
    {
        $concepts = Concept::find()->all();
        $labels = [];
        $greens = [];
        $reds = [];

        foreach ($concepts as $c) {
            $labels[] = $c->title;
            $greens[] = $c->getFeedbacks()->where(['status' => 'understood'])->count();
            $reds[] = $c->getFeedbacks()->where(['status' => 'not_understood'])->count();
        }

        return $this->render('dashboard', compact('concepts', 'labels', 'greens', 'reds'));
    }

    /**
     * Lists all Concept models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $concepts = Concept::find()->all();
        return $this->render('index', compact('concepts'));
    }

    public function actionCreate()
    {
        if (Yii::$app->user->isGuest || !Yii::$app->user->identity->isAdmin()) {
            throw new ForbiddenHttpException('Only admins can create concepts.');
        }

        $model = new Concept();

        // Fetch lectures for the dropdown
        $lectures = Yii::$app->db->createCommand('SELECT id, title FROM lecture')->queryAll();
        $lectureItems = ArrayHelper::map($lectures, 'id', 'title');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'lectureItems' => $lectureItems,
        ]);
    }

    public function actionSubmitFeedback($concept_id, $status)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $userId = Yii::$app->user->id;

        // Validate status
        if (!in_array($status, ['understood', 'not_understood'])) {
            Yii::$app->session->setFlash('error', 'Invalid feedback status.');
            return $this->redirect(['index']);
        }

        // Find existing feedback or create a new one
        $feedback = Feedback::findOne(['user_id' => $userId, 'concept_id' => $concept_id]);
        if ($feedback === null) {
            $feedback = new Feedback();
            $feedback->user_id = $userId;
            $feedback->concept_id = $concept_id;
        }

        $feedback->status = $status;

        if ($feedback->save()) {
            Yii::$app->session->setFlash('success', 'Your feedback has been recorded.');
        } else {
            Yii::$app->session->setFlash('error', 'Could not save your feedback.');
        }

        return $this->redirect(['index']);
    }

    public function actionRemoveFeedback($concept_id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $userId = Yii::$app->user->id;
        $feedback = Feedback::findOne(['user_id' => $userId, 'concept_id' => $concept_id]);
        if ($feedback) {
            $feedback->delete();
            Yii::$app->session->setFlash('success', 'Your feedback has been removed.');
        } else {
            Yii::$app->session->setFlash('error', 'No feedback found to remove.');
        }
        return $this->redirect(['index']);
    }

    public function actionViewFeedback($id, $status)
    {
        $concept = Concept::findOne($id);
        if (!$concept) {
            throw new NotFoundHttpException('Concept not found.');
        }

        if ($status === 'not_understood' && (!Yii::$app->user->identity || !Yii::$app->user->identity->isAdmin())) {
            throw new ForbiddenHttpException('Only admins can view the list of students who did not understand.');
        }

        $feedbacks = Feedback::find()->where(['concept_id' => $id, 'status' => $status])->all();

        return $this->render('view-feedback', [
            'concept' => $concept,
            'feedbacks' => $feedbacks,
            'status' => $status,
        ]);
    }

    public function actionMyFeedback()
    {
        $feedbacks = Feedback::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->with('concept')
            ->all();

        return $this->render('my-feedback', compact('feedbacks'));
    }

    public function actionMyReport()
    {
        $feedbacks = Feedback::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->with('concept')
            ->all();

        return $this->render('my-report', compact('feedbacks'));
    }

    public function actionMyReportPdf()
    {
        $feedbacks = Feedback::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->with('concept')
            ->all();

        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Post Session Learning System');
        $pdf->SetTitle('My Feedback Report');
        $pdf->AddPage();

        $html = $this->renderPartial('my-report-pdf', compact('feedbacks'));
        $pdf->writeHTML($html, true, false, true, false, '');

        return $pdf->Output('feedback-report.pdf', 'D');
    }
}
