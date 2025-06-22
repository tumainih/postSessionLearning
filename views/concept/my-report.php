<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Feedback[] $feedbacks */

$this->title = 'My Feedback Report';
$this->params['breadcrumbs'][] = ['label' => 'Concepts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$understoodCount = 0;
$notUnderstoodCount = 0;

foreach ($feedbacks as $feedback) {
    if ($feedback->isUnderstood()) {
        $understoodCount++;
    } else {
        $notUnderstoodCount++;
    }
}
?>

<div class="concept-my-report">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Feedback Summary</h3>
                </div>
                <div class="card-body">
                    <p><strong>Concepts Understood:</strong> <?= $understoodCount ?></p>
                    <p><strong>Concepts Not Understood:</strong> <?= $notUnderstoodCount ?></p>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <h3>Detailed Feedback</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Concept</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($feedbacks as $feedback): ?>
                <tr>
                    <td><?= Html::encode($feedback->concept->title) ?></td>
                    <td>
                        <?php if ($feedback->isUnderstood()): ?>
                            <span class="badge badge-success">Understood</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Not Understood</span>
                        <?php endif; ?>
                    </td>
                    <td><?= Yii::$app->formatter->asDatetime($feedback->created_at) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p>
        <?= Html::a('Download PDF', ['my-report-pdf'], ['class' => 'btn btn-primary']) ?>
    </p>
</div>
