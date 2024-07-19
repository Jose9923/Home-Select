<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ReservaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reservas';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reserva-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Crear Reserva', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'apartamento_id',
                'value' => 'apartamento.nombre',
                'label' => 'Apartamento',
            ],
            [
                'attribute' => 'cliente_id',
                'value' => 'cliente.nombre',
                'label' => 'Cliente',
            ],
            'fecha_inicio:date',
            'fecha_fin:date',
            [
                'attribute' => 'estado_id',
                'value' => 'estado.nombre',
                'label' => 'Estado',
            ],
            [
                'attribute' => 'pago.valor',
                'value' => function($model) {
                    return $model->pago ? $model->pago->valor : 'No hay pago';
                },
                'label' => 'Pago',
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Acciones',
                'template' => ' {update} {delete}',
            ],
        ],
    ]); ?>

</div>
