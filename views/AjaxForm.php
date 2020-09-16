<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Edge */
/* @var $form ActiveForm */
?>
<div class="AjaxForm">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'id_first_node') ?>
        <?= $form->field($model, 'id_second_node') ?>
        <?= $form->field($model, 'weight') ?>
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- AjaxForm -->
