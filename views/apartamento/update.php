<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Apartamento */
/* @var $form yii\widgets\ActiveForm */
/* @var $tarifa app\models\Tarifa */
/* @var $tipoApartamentos array */
/* @var $ciudades array */
/* @var $tipoTarifas array */

$this->title = 'Actualizar Apartamento: ' . $model->nombre;
//$this->params['breadcrumbs'][] = ['label' => 'Apartamentos', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->nombre, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="apartamento-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="apartamento-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'direccion')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'tipo_apartamento_id')->dropDownList($tipoApartamentos, [
            'prompt' => 'Seleccionar Tipo de Apartamento',
            'id' => 'tipo-apartamento-id'
        ]) ?>
        <?= $form->field($model, 'ciudad_id')->dropDownList($ciudades, [
            'prompt' => 'Seleccionar Ciudad'
        ]) ?>

        <?= $form->field($tarifa, 'tipo_tarifa_id')->dropDownList([], [
            'prompt' => 'Seleccionar Tipo de Tarifa',
            'id' => 'tipo-tarifa-id'
        ]) ?>
        <?= $form->field($tarifa, 'fecha_inicio')->input('date', ['value' => $tarifa->fecha_inicio]) ?>
        <?= $form->field($tarifa, 'fecha_fin')->input('date', ['value' => $tarifa->fecha_fin]) ?>
        <?= $form->field($tarifa, 'valor')->textInput(['type' => 'number', 'step' => '0.01', 'value' => $tarifa->valor]) ?>

        <div class="form-group">
            <?= Html::submitButton('Actualizar', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>

<?php
$this->registerJs("
function updateTarifaOptions() {
    var tipoApartamentoId = $('#tipo-apartamento-id').val();
    var options = {};

    if (tipoApartamentoId == 1) { // Suponiendo que 1 es el ID para 'Corporativo'
        options = {
            '1': 'Mensual' // Suponiendo que 1 es el ID para 'Mensual'
        };
    } else if (tipoApartamentoId == 2) { // Suponiendo que 2 es el ID para 'Turístico'
        options = {
            '2': 'Diario' // Suponiendo que 2 es el ID para 'Diario'
        };
    }

    var select = $('#tipo-tarifa-id');
    select.empty();

    $.each(options, function(key, value) {
        select.append($('<option></option>').attr('value', key).text(value));
    });

    // Selecciona la opción correcta si ya hay un valor seleccionado
    select.val(" . $tarifa->tipo_tarifa_id . ");
}

$('#tipo-apartamento-id').on('change', updateTarifaOptions);

// Trigger change event on page load to set the correct tarifa options
updateTarifaOptions();
");

?>

