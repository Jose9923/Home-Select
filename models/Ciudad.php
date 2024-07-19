<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ciudad".
 *
 * @property int $id
 * @property string $nombre
 *
 * @property Apartamento[] $apartamentos
 */
class Ciudad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ciudad';
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
     * Gets query for [[Apartamentos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApartamentos()
    {
        return $this->hasMany(Apartamento::class, ['ciudad_id' => 'id']);
    }
}
