<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tarifa".
 *
 * @property int $id
 * @property int $apartamento_id
 * @property int $tipo_tarifa_id
 * @property string $fecha_inicio
 * @property string $fecha_fin
 * @property float $valor
 *
 * @property Apartamento $apartamento
 * @property TipoTarifa $tipoTarifa
 */
class Tarifa extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tarifa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['apartamento_id', 'tipo_tarifa_id', 'fecha_inicio', 'fecha_fin', 'valor'], 'required'],
            [['apartamento_id', 'tipo_tarifa_id'], 'integer'],
            [['fecha_inicio', 'fecha_fin'], 'safe'],
            [['valor'], 'number'],
            [['apartamento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Apartamento::class, 'targetAttribute' => ['apartamento_id' => 'id']],
            [['tipo_tarifa_id'], 'exist', 'skipOnError' => true, 'targetClass' => TipoTarifa::class, 'targetAttribute' => ['tipo_tarifa_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'apartamento_id' => 'Apartamento ID',
            'tipo_tarifa_id' => 'Tipo Tarifa ID',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin',
            'valor' => 'Valor',
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
     * Gets query for [[TipoTarifa]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipoTarifa()
    {
        return $this->hasOne(TipoTarifa::class, ['id' => 'tipo_tarifa_id']);
    }
}
