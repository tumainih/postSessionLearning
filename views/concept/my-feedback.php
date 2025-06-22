<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Feedback[] $feedbacks */

$this->title = 'My Feedback History';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="concept-feedback-history">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (empty($feedbacks)): ?>
        <div class="alert alert-warning">You haven’t submitted any feedback yet.</div>
    <?php else: ?>
        <ul class="list-group">
            <?php foreach ($feedbacks as $fb): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong><?= Html::encode($fb->concept->title ?? 'Unknown Concept') ?></strong><br>
                        <small class="text-muted">
                            <?= Yii::$app->formatter->asDatetime($fb->created_at) ?>
                        </small>
                    </div>
                    <div>
                        <?= $fb->status === 'understood'
                            ? '<span class="text-success">🟢 Understood</span>'
                            : '<span class="text-danger">🔴 Not Understood</span>' ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
