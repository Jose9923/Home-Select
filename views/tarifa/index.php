<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TarifaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tarifas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tarifa-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Crear Tarifa', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'apartamento_id',
            'tipo_tarifa_id',
            'fecha_inicio',
            'fecha_fin',
            'valor',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

