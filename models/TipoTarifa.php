<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_tarifa".
 *
 * @property int $id
 * @property string $nombre
 *
 * @property Tarifa[] $tarifas
 */
class TipoTarifa extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_tarifa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['nombre'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
        ];
    }

    /**
     * Gets query for [[Tarifas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTarifas()
    {
        return $this->hasMany(Tarifa::class, ['tipo_tarifa_id' => 'id']);
    }
}
