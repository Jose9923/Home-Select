<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "reserva".
 *
 * @property int $id
 * @property string $codigo
 * @property int $apartamento_id
 * @property int $cliente_id
 * @property string $fecha_inicio
 * @property string $fecha_fin
 * @property int $estado_id
 *
 * @property Apartamento $apartamento
 * @property Cliente $cliente
 * @property Estado $estado
 * @property Pago[] $pagos
 */
class Reserva extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reserva';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['apartamento_id', 'cliente_id', 'fecha_inicio', 'fecha_fin', 'estado_id'], 'required'],
            [['apartamento_id', 'cliente_id', 'estado_id'], 'integer'],
            [['fecha_inicio', 'fecha_fin'], 'safe'],
            [['codigo'], 'string', 'max' => 255],
            [['codigo'], 'unique'],
            [['apartamento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Apartamento::class, 'targetAttribute' => ['apartamento_id' => 'id']],
            [['cliente_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::class, 'targetAttribute' => ['cliente_id' => 'id']],
            [['estado_id'], 'exist', 'skipOnError' => true, 'targetClass' => Estado::class, 'targetAttribute' => ['estado_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'codigo' => 'Codigo',
            'apartamento_id' => 'Apartamento',
            'cliente_id' => 'Cliente',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin',
            'estado_id' => 'Estado',
        ];
    }

    /**
     * Gets query for [[Apartamento]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApartamento()
    {
        return $this->hasOne(Apartamento::class, ['id' => 'apartamento_id']);
    }

    /**
     * Gets query for [[Cliente]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Cliente::class, ['id' => 'cliente_id']);
    }

    /**
     * Gets query for [[Estado]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEstado()
    {
        return $this->hasOne(Estado::class, ['id' => 'estado_id']);
    }

    /**
     * Gets query for [[Pagos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPago()
    {
        return $this->hasOne(Pago::class, ['reserva_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->codigo = Yii::$app->security->generateRandomString(10);
            }
            return true;
        }
        return false;
    }

    public function search($params)
    {
        $query = Reserva::find()->joinWith(['apartamento', 'cliente', 'estado', 'pago']);
    
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
    
        $dataProvider->sort->attributes['apartamento.nombre'] = [
            'asc' => ['apartamento.nombre' => SORT_ASC],
            'desc' => ['apartamento.nombre' => SORT_DESC],
        ];
    
        $dataProvider->sort->attributes['cliente.nombre'] = [
            'asc' => ['cliente.nombre' => SORT_ASC],
            'desc' => ['cliente.nombre' => SORT_DESC],
        ];
    
        $dataProvider->sort->attributes['estado.nombre'] = [
            'asc' => ['estado.nombre' => SORT_ASC],
            'desc' => ['estado.nombre' => SORT_DESC],
        ];
    
        $dataProvider->sort->attributes['pago.valor'] = [
            'asc' => ['pago.valor' => SORT_ASC],
            'desc' => ['pago.valor' => SORT_DESC],
        ];
    
        $this->load($params);
    
        if (!$this->validate()) {
            return $dataProvider;
        }
    
        $query->andFilterWhere([
            'id' => $this->id,
            'apartamento_id' => $this->apartamento_id,
            'cliente_id' => $this->cliente_id,
            'estado_id' => $this->estado_id,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
        ]);
    
        $query->andFilterWhere(['like', 'codigo', $this->codigo]);
    
        return $dataProvider;
    }
}
