<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pago".
 *
 * @property int $id
 * @property int $reserva_id
 * @property int $tipo_pago_id
 * @property float $valor
 *
 * @property Reserva $reserva
 * @property TipoPago $tipoPago
 */
class Pago extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pago';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reserva_id', 'tipo_pago_id', 'valor'], 'required'],
            [['reserva_id', 'tipo_pago_id'], 'integer'],
            [['valor'], 'number'],
            [['reserva_id'], 'exist', 'skipOnError' => true, 'targetClass' => Reserva::class, 'targetAttribute' => ['reserva_id' => 'id']],
            [['tipo_pago_id'], 'exist', 'skipOnError' => true, 'targetClass' => TipoPago::class, 'targetAttribute' => ['tipo_pago_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'reserva_id' => 'Reserva ID',
            'tipo_pago_id' => 'Tipo Pago ID',
            'valor' => 'Valor',
        ];
    }

    /**
     * Gets query for [[Reserva]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReserva()
    {
        return $this->hasOne(Reserva::class, ['id' => 'reserva_id']);
    }

    /**
     * Gets query for [[TipoPago]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipoPago()
    {
        return $this->hasOne(TipoPago::class, ['id' => 'tipo_pago_id']);
    }
}
