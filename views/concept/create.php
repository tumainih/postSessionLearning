<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Concept */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'New Concept';
$this->params['breadcrumbs'][] = ['label' => 'Concepts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="concept-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="concept-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'lecture_id')->dropDownList($lectureItems, ['prompt' => 'Select a Lecture']) ?>

        <div class="form-group">
            <?= Html::submitButton('Save Concept', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
