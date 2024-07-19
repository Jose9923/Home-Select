<?php

namespace app\controllers;

use Yii;
use app\models\Apartamento;
use app\models\Tarifa;
use app\models\Ciudad;
use app\models\TipoApartamento;
use app\models\TipoTarifa;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ApartamentoController implements the CRUD actions for Apartamento model.
 */
class ApartamentoController extends Controller
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
     * Lists all Apartamento models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new Apartamento();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    

    /**
     * Displays a single Apartamento model.
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
     * Creates a new Apartamento model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Apartamento();
        $tarifa = new Tarifa();
    
        // Obtener los datos para los dropdowns
        $tipoApartamentos = TipoApartamento::find()->select(['nombre', 'id'])->indexBy('id')->column();
        $ciudades = Ciudad::find()->select(['nombre', 'id'])->indexBy('id')->column();
        $tipoTarifas = TipoTarifa::find()->select(['nombre', 'id'])->indexBy('id')->column();
    
        if ($model->load(Yii::$app->request->post()) && $tarifa->load(Yii::$app->request->post())) {
            // Buscar apartamento existente con el mismo nombre, dirección, ciudad y tipo de apartamento
            $existingApartamento = Apartamento::find()
                ->where([
                    'nombre' => $model->nombre,
                    'direccion' => $model->direccion,
                    'ciudad_id' => $model->ciudad_id,
                    'tipo_apartamento_id' => $model->tipo_apartamento_id
                ])
                ->one();
    
            if ($existingApartamento) {
                // Asignar el ID del apartamento existente al modelo Tarifa
                $model->id = $existingApartamento->id;
                $tarifa->apartamento_id = $existingApartamento->id;
    
                // Validar que la tarifa no se solape con otras tarifas existentes para el mismo apartamento
                $conflictingTarifa = Tarifa::find()
                    ->where(['apartamento_id' => $existingApartamento->id])
                    ->andWhere(['or',
                        ['and', ['<', 'fecha_inicio', $tarifa->fecha_fin], ['>', 'fecha_inicio', $tarifa->fecha_inicio]],
                        ['and', ['<', 'fecha_fin', $tarifa->fecha_fin], ['>', 'fecha_fin', $tarifa->fecha_inicio]],
                        ['and', ['<=', 'fecha_inicio', $tarifa->fecha_inicio], ['>=', 'fecha_fin', $tarifa->fecha_fin]]
                    ])
                    ->exists();
    
                if ($conflictingTarifa) {
                    Yii::$app->session->setFlash('error', 'Las fechas de la tarifa se solapan con otra tarifa existente.');
                } else {
                    // Ahora valida y guarda el modelo Tarifa
                    if ($tarifa->validate() && $tarifa->save()) {
                        Yii::info('Tarifa guardada exitosamente para el apartamento existente.', __METHOD__);
                        return $this->redirect(['index']);
                    } else {
                        Yii::error('Error al guardar la Tarifa: ' . json_encode($tarifa->errors), __METHOD__);
                        Yii::$app->session->setFlash('error', 'Error al guardar la Tarifa: ' . json_encode($tarifa->errors));
                    }
                }
            } else {
                // No existe apartamento, guardar nuevo apartamento y tarifa
                if ($model->validate() && $model->save(false)) {
                    $tarifa->apartamento_id = $model->id;
    
                    // Validar que la tarifa no se solape con otras tarifas existentes para el nuevo apartamento
                    $conflictingTarifa = Tarifa::find()
                        ->where(['apartamento_id' => $model->id])
                        ->andWhere(['or',
                            ['and', ['<', 'fecha_inicio', $tarifa->fecha_fin], ['>', 'fecha_inicio', $tarifa->fecha_inicio]],
                            ['and', ['<', 'fecha_fin', $tarifa->fecha_fin], ['>', 'fecha_fin', $tarifa->fecha_inicio]],
                            ['and', ['<=', 'fecha_inicio', $tarifa->fecha_inicio], ['>=', 'fecha_fin', $tarifa->fecha_fin]]
                        ])
                        ->exists();
    
                    if ($conflictingTarifa) {
                        Yii::$app->session->setFlash('error', 'Las fechas de la tarifa se solapan con otra tarifa existente.');
                    } else {
                        if ($tarifa->validate() && $tarifa->save()) {
                            Yii::info('Apartamento y Tarifa guardados exitosamente.', __METHOD__);
                            return $this->redirect(['index']);
                        } else {
                            Yii::error('Error al guardar la Tarifa: ' . json_encode($tarifa->errors), __METHOD__);
                            Yii::$app->session->setFlash('error', 'Error al guardar la Tarifa: ' . json_encode($tarifa->errors));
                        }
                    }
                } else {
                    Yii::error('Error al guardar el Apartamento: ' . json_encode($model->errors), __METHOD__);
                    Yii::$app->session->setFlash('error', 'Error al guardar el Apartamento: ' . json_encode($model->errors));
                }
            }
        }
    
        return $this->render('create', [
            'model' => $model,
            'tarifa' => $tarifa,
            'tipoApartamentos' => $tipoApartamentos,
            'ciudades' => $ciudades,
            'tipoTarifas' => $tipoTarifas,
        ]);
    }
    



    /**
     * Updates an existing Apartamento model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */



     public function actionUpdate($id)
     {
         $model = $this->findModel($id);
         $tarifa = Tarifa::find()->where(['apartamento_id' => $id])->one();
     
         // Obtener los datos para los dropdowns
         $tipoApartamentos = TipoApartamento::find()->select(['nombre', 'id'])->indexBy('id')->column();
         $ciudades = Ciudad::find()->select(['nombre', 'id'])->indexBy('id')->column();
         $tipoTarifas = TipoTarifa::find()->select(['nombre', 'id'])->indexBy('id')->column();
     
         if ($model->load(Yii::$app->request->post()) && $tarifa->load(Yii::$app->request->post())) {
             // Buscar apartamento existente con el mismo nombre, dirección, ciudad y tipo de apartamento
             $existingApartamento = Apartamento::find()
                 ->where([
                     'nombre' => $model->nombre,
                     'direccion' => $model->direccion,
                     'ciudad_id' => $model->ciudad_id,
                     'tipo_apartamento_id' => $model->tipo_apartamento_id
                 ])
                 ->one();
     
             if ($existingApartamento && $existingApartamento->id != $model->id) {
                 // El apartamento ya existe con los mismos detalles, no es posible actualizar
                 Yii::$app->session->setFlash('error', 'Ya existe un apartamento con el mismo nombre, dirección, ciudad y tipo.');
             } else {
                 // Validar que la tarifa no se solape con otras tarifas existentes para el mismo apartamento
                 $conflictingTarifa = Tarifa::find()
                     ->where(['apartamento_id' => $model->id])
                     ->andWhere(['or',
                         ['and', ['<', 'fecha_inicio', $tarifa->fecha_fin], ['>', 'fecha_inicio', $tarifa->fecha_inicio]],
                         ['and', ['<', 'fecha_fin', $tarifa->fecha_fin], ['>', 'fecha_fin', $tarifa->fecha_inicio]],
                         ['and', ['<=', 'fecha_inicio', $tarifa->fecha_inicio], ['>=', 'fecha_fin', $tarifa->fecha_fin]]
                     ])
                     ->andWhere(['<>', 'id', $tarifa->id]) // Excluir la tarifa actual de la comprobación
                     ->exists();
     
                 if ($conflictingTarifa) {
                     Yii::$app->session->setFlash('error', 'Las fechas de la tarifa se solapan con otra tarifa existente.');
                 } else {
                     // Validar y guardar el modelo Apartamento y Tarifa
                     if ($model->validate() && $model->save(false)) {
                         $tarifa->apartamento_id = $model->id;
                         if ($tarifa->validate() && $tarifa->save()) {
                             Yii::info('Apartamento y Tarifa actualizados exitosamente.', __METHOD__);
                             return $this->redirect(['index']);
                         } else {
                             Yii::error('Error al actualizar la Tarifa: ' . json_encode($tarifa->errors), __METHOD__);
                             Yii::$app->session->setFlash('error', 'Error al actualizar la Tarifa: ' . json_encode($tarifa->errors));
                         }
                     } else {
                         Yii::error('Error al actualizar el Apartamento: ' . json_encode($model->errors), __METHOD__);
                         Yii::$app->session->setFlash('error', 'Error al actualizar el Apartamento: ' . json_encode($model->errors));
                     }
                 }
             }
         }
     
         return $this->render('update', [
             'model' => $model,
             'tarifa' => $tarifa,
             'tipoApartamentos' => $tipoApartamentos,
             'ciudades' => $ciudades,
             'tipoTarifas' => $tipoTarifas,
         ]);
     }
 
    /**
     * Deletes an existing Apartamento model.
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
     * Finds the Apartamento model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Apartamento the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Apartamento::findOne($id)) !== null) {
            return $model;
        }
    
        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    protected function findTarifaModel($id)
    {
        if (($model = Tarifa::findOne($id)) !== null) {
            return $model;
        }
    
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
