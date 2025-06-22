<?php
use yii\helpers\Html;

/** @var $this yii\web\View */
/** @var $concept app\models\Concept */
/** @var $feedbacks app\models\Feedback[] */
/** @var $status string */

$this->title = 'Feedback for: ' . $concept->title;
$this->params['breadcrumbs'][] = ['label' => 'Concepts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="concept-feedback-list">
    <h2><?= Html::encode($this->title) ?></h2>
    <h4>Status: <?= $status === 'understood' ? '🟢 Understood' : '🔴 Not Understood' ?></h4>

    <?php if (empty($feedbacks)): ?>
        <div class="alert alert-info">No students have given this feedback yet.</div>
    <?php else: ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student Name</th>
                    <?php if ($status === 'understood'): ?>
                        <th>Contact Number</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($feedbacks as $i => $feedback): ?>
                    <?php $user = $feedback->user; ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= Html::encode($user ? $user->username : 'Unknown') ?></td>
                        <?php if ($status === 'understood'): ?>
                            <td><?= Html::encode($user ? $user->contact_number : '-') ?></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="mt-3">
        <?= Html::a('← Back to Concepts', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>
</div>

<style>
.card {
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.card-header {
    background-color: <?= $status === 'understood' ? '#d4edda' : '#f8d7da' ?>;
    border-bottom: 1px solid #dee2e6;
    border-radius: 12px 12px 0 0 !important;
}

.table th {
    font-weight: 600;
}

.table-responsive {
    border-radius: 8px;
    overflow: hidden;
}
</style>
