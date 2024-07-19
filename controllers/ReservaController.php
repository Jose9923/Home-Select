<?php

namespace app\controllers;

use yii;
use app\models\Reserva;
use app\models\Pago;
use app\models\Estado;
use app\models\Tarifa;
use yii\data\ActiveDataProvider;
use app\models\Apartamento;
use app\models\Cliente;
use app\models\TipoPago;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ReservaController implements the CRUD actions for Reserva model.
 */
class ReservaController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Reserva models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new Reserva();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Displays a single Reserva model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Reserva model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Reserva();
        $pago = new Pago();
    
        // Obtener los datos para los dropdowns
        $apartamentos = Apartamento::find()->select(['nombre', 'id'])->indexBy('id')->column();
        $clientes = Cliente::find()->select(['CONCAT(nombre, " ", apellido) AS nombre_completo', 'id'])->indexBy('id')->column();
        Yii::info('1');
        try {
            if ($model->load(Yii::$app->request->post())) {
                Yii::debug('Datos POST recibidos: ' . print_r(Yii::$app->request->post(), true));
                Yii::info('2');
                if ($model->validate()) {
                    // Check availability
                    Yii::info('3');
                    $exists = Reserva::find()
                        ->where(['apartamento_id' => $model->apartamento_id])
                        ->andWhere(['estado_id' => Estado::findOne(['nombre' => 'Activa'])->id])
                        ->andWhere(['<', 'fecha_inicio', $model->fecha_fin])
                        ->andWhere(['>', 'fecha_fin', $model->fecha_inicio])
                        ->exists();
    
                    if ($exists) {
                        Yii::info('4');
                        Yii::$app->session->setFlash('error', 'El apartamento no está disponible en las fechas seleccionadas.');
                    } else {
                        $apartamento = Apartamento::findOne($model->apartamento_id);
                        $tarifa = Tarifa::find()
                            ->where(['apartamento_id' => $model->apartamento_id])
                            ->andWhere(['<=', 'fecha_inicio', $model->fecha_inicio])
                            ->andWhere(['>=', 'fecha_fin', $model->fecha_fin])
                            ->one();
    
                        if ($apartamento && $tarifa) {
                            Yii::info('5');
                            // Calculate the payment
                            if ($apartamento->tipoApartamento->nombre == 'Corporativo') {
                                Yii::info('6');
                                $meses = (strtotime($model->fecha_fin) - strtotime($model->fecha_inicio)) / (60 * 60 * 24 * 30);
                                $alquiler = $tarifa->valor * ceil($meses);
                                $tasa_servicio = $alquiler * 0.03;
                                $total_pago = $alquiler + $tasa_servicio;
    
                                $pago->tipo_pago_id = TipoPago::findOne(['nombre' => 'Alquiler'])->id;
                                $pago->valor = $total_pago;
                            } elseif ($apartamento->tipoApartamento->nombre == 'Turístico') {
                                Yii::info('7');
                                $dias = (strtotime($model->fecha_fin) - strtotime($model->fecha_inicio)) / (60 * 60 * 24);
                                $alquiler = $tarifa->valor * ceil($dias);
                                $tasa_reserva = 150;
                                $total_pago = $alquiler + $tasa_reserva;
    
                                $pago->tipo_pago_id = TipoPago::findOne(['nombre' => 'Tasa de Reserva'])->id;
                                $pago->valor = $total_pago;
                            }
    
                            // Save the reservation and payment
                            $transaction = Yii::$app->db->beginTransaction();
                            try {
                                if ($model->save()) {
                                    // Asignar el reserva_id al pago después de guardar la reserva
                                    $pago->reserva_id = $model->id;
                                    if ($pago->save()) {
                                        $transaction->commit();
                                        Yii::$app->session->setFlash('success', 'Reserva creada correctamente.');
                                        return $this->redirect(['index']);
                                    } else {
                                        $transaction->rollBack();
                                        Yii::$app->session->setFlash('error', 'No se pudo guardar el pago. Errores: ' . print_r($pago->errors, true));
                                    }
                                } else {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'No se pudo guardar la reserva. Errores: ' . print_r($model->errors, true));
                                }
                            } catch (\Exception $e) {
                                $transaction->rollBack();
                                throw $e;
                            }
                        } else {
                            Yii::$app->session->setFlash('error', 'No se encontró una tarifa válida para las fechas seleccionadas.');
                        }
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Errores de validación: ' . print_r($model->errors, true));
                }
            }
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Ocurrió un error: ' . $e->getMessage());
        }
    
        return $this->render('create', [
            'model' => $model,
            'pago' => $pago,
            'apartamentos' => $apartamentos,
            'clientes' => $clientes,
        ]);
    }
    
    /**
     * Updates an existing Reserva model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $pago = Pago::findOne(['reserva_id' => $id]);
    
        // Obtener los datos para los dropdowns
        $apartamentos = Apartamento::find()->select(['nombre', 'id'])->indexBy('id')->column();
        $clientes = Cliente::find()->select(['CONCAT(nombre, " ", apellido) AS nombre_completo', 'id'])->indexBy('id')->column();
    
        try {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                // Check availability
                $exists = Reserva::find()
                    ->where(['apartamento_id' => $model->apartamento_id])
                    ->andWhere(['estado_id' => Estado::findOne(['nombre' => 'Activa'])->id])
                    ->andWhere(['<', 'fecha_inicio', $model->fecha_fin])
                    ->andWhere(['>', 'fecha_fin', $model->fecha_inicio])
                    ->andWhere(['!=', 'id', $id])
                    ->exists();
    
                if ($exists) {
                    Yii::$app->session->setFlash('error', 'El apartamento no está disponible en las fechas seleccionadas.');
                    return $this->render('update', [
                        'model' => $model,
                        'pago' => $pago,
                        'apartamentos' => $apartamentos,
                        'clientes' => $clientes,
                    ]);
                }
    
                // Save the reservation
                if ($model->save()) {
                    $apartamento = Apartamento::findOne($model->apartamento_id);
                    $tarifa = Tarifa::find()
                        ->where(['apartamento_id' => $model->apartamento_id])
                        ->andWhere(['<=', 'fecha_inicio', $model->fecha_inicio])
                        ->andWhere(['>=', 'fecha_fin', $model->fecha_fin])
                        ->one();
    
                    if ($apartamento && $tarifa) {
                        // Calculate the payment
                        if ($apartamento->tipoApartamento->nombre == 'Corporativo') {
                            $meses = (strtotime($model->fecha_fin) - strtotime($model->fecha_inicio)) / (60 * 60 * 24 * 30);
                            $alquiler = $tarifa->valor * ceil($meses);
                            $tasa_servicio = $alquiler * 0.03;
                            $total_pago = $alquiler + $tasa_servicio;
    
                            $pago->tipo_pago_id = TipoPago::findOne(['nombre' => 'Alquiler'])->id;
                            $pago->reserva_id = $model->id;
                            $pago->valor = $total_pago;
                        } elseif ($apartamento->tipoApartamento->nombre == 'Turístico') {
                            $dias = (strtotime($model->fecha_fin) - strtotime($model->fecha_inicio)) / (60 * 60 * 24);
                            $alquiler = $tarifa->valor * ceil($dias);
                            $tasa_reserva = 150;
                            $total_pago = $alquiler + $tasa_reserva;
    
                            $pago->tipo_pago_id = TipoPago::findOne(['nombre' => 'Tasa de Reserva'])->id;
                            $pago->reserva_id = $model->id;
                            $pago->valor = $total_pago;
                        }
    
                        if ($pago->save()) {
                            Yii::$app->session->setFlash('success', 'Reserva actualizada correctamente.');
                            return $this->redirect(['index']);
                        } else {
                            Yii::$app->session->setFlash('error', 'No se pudo guardar el pago. Errores: ' . print_r($pago->errors, true));
                        }
                    } else {
                        Yii::$app->session->setFlash('error', 'No se encontró una tarifa válida para las fechas seleccionadas.');
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'No se pudo guardar la reserva. Errores: ' . print_r($model->errors, true));
                }
            }
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Ocurrió un error: ' . $e->getMessage());
        }
    
        return $this->render('update', [
            'model' => $model,
            'pago' => $pago,
            'apartamentos' => $apartamentos,
            'clientes' => $clientes,
        ]);
    }
    

    /**
     * Deletes an existing Reserva model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Reserva model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Reserva the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Reserva::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
