<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Tarifa */

$this->title = 'Crear Tarifa';
$this->params['breadcrumbs'][] = ['label' => 'Tarifas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tarifa-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="tarifa-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'apartamento_id')->dropDownList($apartamentos) ?>
        <?= $form->field($model, 'tipo_tarifa_id')->dropDownList($tipoTarifas) ?>
        <?= $form->field($model, 'fecha_inicio')->input('date') ?>
        <?= $form->field($model, 'fecha_fin')->input('date') ?>
        <?= $form->field($model, 'valor')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
