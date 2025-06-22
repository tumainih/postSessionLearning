<?php
use yii\helpers\Html;
use app\models\Feedback;

/** @var yii\web\View $this */
/** @var app\models\Concept[] $concepts */

$this->title = 'Post Session Learning';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="concept-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->user->identity && Yii::$app->user->identity->isAdmin()): ?>
        <div class="mb-4">
            <?= Html::a('➕ Add New Concept', ['concept/create'], ['class' => 'btn btn-primary']) ?>
        </div>
    <?php endif; ?>

    <?php if (empty($concepts)): ?>
        <div class="alert alert-info">
            <p>No concepts found. <?= Yii::$app->user->identity && Yii::$app->user->identity->isAdmin() ? 'Click the + button above to add a concept.' : 'Please wait for concepts to be added by admin.' ?></p>
        </div>
    <?php else: ?>
        <div class="concept-list">
            <?php foreach ($concepts as $concept): ?>
                <?php
                $userFeedback = Feedback::findOne(['user_id' => Yii::$app->user->id, 'concept_id' => $concept->id]);
                ?>
                <div class="concept-item card mb-4">
                    <div class="card-body">
                        <h4 class="card-title"><?= Html::encode($concept->title) ?></h4>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="text-center">
                                    <?php if ($userFeedback && $userFeedback->status === 'understood'): ?>
                                        <!-- User already marked as understood -->
                                        <button class="btn btn-success btn-lg mb-2" style="width: 200px;" disabled>🟢 Understood</button>
                                        <div class="mb-2">
                                            <small class="text-success">✅ You marked this as understood</small>
                                        </div>
                                    <?php else: ?>
                                        <!-- User can mark as understood -->
                                        <?= Html::a('🟢 Understood', ['concept/submit-feedback', 'concept_id' => $concept->id, 'status' => 'understood'], [
                                            'class' => 'btn btn-success btn-lg mb-2',
                                            'style' => 'width: 200px;',
                                            'data' => $userFeedback && $userFeedback->status === 'not_understood' ? ['confirm' => 'Are you sure you want to change your feedback to "Understood"?'] : []
                                        ]) ?>
                                        <?php if ($userFeedback && $userFeedback->status === 'not_understood'): ?>
                                            <div class="mb-2">
                                                <small class="text-info">💡 You can change your feedback if you now understand this concept</small>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    
                                    <br>
                                    <?php 
                                    $understoodCount = $concept->getFeedbacks()->where(['status' => 'understood'])->count();
                                    if ($understoodCount > 0): 
                                    ?>
                                        <?= Html::a("($understoodCount students)", ['concept/view-feedback', 'id' => $concept->id, 'status' => 'understood'], [
                                            'class' => 'text-success',
                                            'style' => 'text-decoration: underline; cursor: pointer;'
                                        ]) ?>
                                    <?php else: ?>
                                        <span class="text-muted">(0 students)</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="text-center">
                                    <?php if ($userFeedback && $userFeedback->status === 'not_understood'): ?>
                                        <!-- User already marked as not understood -->
                                        <button class="btn btn-danger btn-lg mb-2" style="width: 200px;" disabled>🔴 Not Understood</button>
                                        <div class="mb-2">
                                            <small class="text-danger">❌ You marked this as not understood</small>
                                        </div>
                                    <?php elseif ($userFeedback && $userFeedback->status === 'understood'): ?>
                                        <!-- User cannot change from understood to not understood -->
                                        <button class="btn btn-danger btn-lg mb-2" style="width: 200px;" disabled>🔴 Not Understood</button>
                                        <div class="mb-2">
                                            <small class="text-muted">🔒 Cannot change from "Understood" to "Not Understood"</small>
                                        </div>
                                    <?php else: ?>
                                        <!-- User can mark as not understood -->
                                        <?= Html::a('🔴 Not Understood', ['concept/submit-feedback', 'concept_id' => $concept->id, 'status' => 'not_understood'], [
                                            'class' => 'btn btn-danger btn-lg mb-2',
                                            'style' => 'width: 200px;'
                                        ]) ?>
                                    <?php endif; ?>
                                    
                                    <br>
                                    <?php 
                                    $notUnderstoodCount = $concept->getFeedbacks()->where(['status' => 'not_understood'])->count();
                                    if ($notUnderstoodCount > 0): 
                                        if (Yii::$app->user->identity && Yii::$app->user->identity->isAdmin()):
                                    ?>
                                        <?= Html::a("($notUnderstoodCount students)", ['concept/view-feedback', 'id' => $concept->id, 'status' => 'not_understood'], [
                                            'class' => 'text-danger',
                                            'style' => 'text-decoration: underline; cursor: pointer;'
                                        ]) ?>
                                    <?php else: ?>
                                        <span class="text-danger">(<?= $notUnderstoodCount ?> students)</span>
                                    <?php 
                                        endif;
                                    else: 
                                    ?>
                                        <span class="text-muted">(0 students)</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <?php if ($userFeedback): ?>
                            <div class="row mt-3">
                                <div class="col-12 text-center">
                                    <?= Html::a('🗑️ Remove My Feedback', ['concept/remove-feedback', 'concept_id' => $concept->id], [
                                        'class' => 'btn btn-outline-secondary btn-sm',
                                        'data' => ['confirm' => 'Are you sure you want to remove your feedback? You can provide new feedback anytime.']
                                    ]) ?>
                                    <div class="mt-1">
                                        <small class="text-muted">You can remove your feedback and provide new feedback later</small>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.concept-item {
    border: 1px solid #ddd;
    border-radius: 8px;
    transition: box-shadow 0.3s ease;
}

.concept-item:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.btn-lg {
    font-size: 1.1rem;
    padding: 12px 24px;
}
</style>
