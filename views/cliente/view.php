<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Apartamento */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Apartamentos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="apartamento-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Crear Apartamento', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'nombre',
            'direccion',
            [
                'attribute' => 'tipoApartamento.nombre',
                'label' => 'Tipo de Apartamento',
            ],
            [
                'attribute' => 'ciudad.nombre',
                'label' => 'Ciudad',
            ],
            [
                'label' => 'Tarifas',
                'format' => 'html',
                'value' => function ($model) {
                    $tarifas = '';
                    foreach ($model->tarifas as $tarifa) {
                        $tarifas .= '<p>Valor: ' . $tarifa->valor . '<br>Fecha Inicio: ' . $tarifa->fecha_inicio . '<br>Fecha Fin: ' . $tarifa->fecha_fin . '</p>';
                    }
                    return $tarifas;
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>

