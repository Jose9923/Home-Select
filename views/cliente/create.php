<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Cliente */

$this->title = 'Crear Cliente';
//$this->params['breadcrumbs'][] = ['label' => 'Clientes', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cliente-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="cliente-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'apellido')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

</div>
