<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Tarifa $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="tarifa-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'apartamento_id')->textInput() ?>

    <?= $form->field($model, 'tipo_tarifa_id')->textInput() ?>

    <?= $form->field($model, 'fecha_inicio')->textInput() ?>

    <?= $form->field($model, 'fecha_fin')->textInput() ?>

    <?= $form->field($model, 'valor')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
