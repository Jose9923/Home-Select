<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TarifaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Apartamentos';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tarifa-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Crear Apartamentos', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'apartamento.nombre',
                'label' => 'Nombre Apartamento',
            ],
            [
                'attribute' => 'apartamento.direccion',
                'label' => 'Dirección',
            ],
            [
                'attribute' => 'tipoApartamentoNombre',
                'label' => 'Tipo de Apartamento',
                'value' => 'apartamento.tipoApartamento.nombre',
            ],
            [
                'attribute' => 'ciudadNombre',
                'label' => 'Ciudad',
                'value' => 'apartamento.ciudad.nombre',
            ],
            [
                'attribute' => 'tarifaValor',
                'label' => 'Valor Tarifa',
                'value' => 'valor',
            ],
            [
                'attribute' => 'tarifaFechaInicio',
                'label' => 'Fecha Inicio Tarifa',
                'value' => 'fecha_inicio',
            ],
            [
                'attribute' => 'tarifaFechaFin',
                'label' => 'Fecha Fin Tarifa',
                'value' => 'fecha_fin',
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Acciones',
                'template' => ' {update} {delete}',
            ],
        ],
    ]); ?>

</div>
