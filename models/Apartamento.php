<?php

namespace app\models;
use yii\data\ActiveDataProvider;
use Yii;

/**
 * This is the model class for table "apartamento".
 *
 * @property int $id
 * @property string $nombre
 * @property string $direccion
 * @property int $tipo_apartamento_id
 * @property int $ciudad_id
 *
 * @property Ciudad $ciudad
 * @property Reserva[] $reservas
 * @property Tarifa[] $tarifas
 * @property TipoApartamento $tipoApartamento
 */
class Apartamento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'apartamento';
    }
    public $tipoApartamentoNombre;
    public $ciudadNombre;
    public $tarifaValor;
    public $tarifaFechaInicio;
    public $tarifaFechaFin;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'direccion', 'tipo_apartamento_id', 'ciudad_id'], 'required'],
            [['tipo_apartamento_id', 'ciudad_id'], 'integer'],
            [['nombre', 'direccion'], 'string', 'max' => 255],
            [['ciudad_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ciudad::class, 'targetAttribute' => ['ciudad_id' => 'id']],
            [['tipo_apartamento_id'], 'exist', 'skipOnError' => true, 'targetClass' => TipoApartamento::class, 'targetAttribute' => ['tipo_apartamento_id' => 'id']],
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
            'direccion' => 'Direccion',
            'tipo_apartamento_id' => 'Tipo Apartamento',
            'ciudad_id' => 'Ciudad',
        ];
    }

    /**
     * Gets query for [[Ciudad]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCiudad()
    {
        return $this->hasOne(Ciudad::class, ['id' => 'ciudad_id']);
    }

    /**
     * Gets query for [[Reservas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReservas()
    {
        return $this->hasMany(Reserva::class, ['apartamento_id' => 'id']);
    }

    /**
     * Gets query for [[Tarifas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTarifas()
    {
        return $this->hasMany(Tarifa::class, ['apartamento_id' => 'id']);
    }

    /**
     * Gets query for [[TipoApartamento]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipoApartamento()
    {
        return $this->hasOne(TipoApartamento::class, ['id' => 'tipo_apartamento_id']);
    }

    public function search($params)
    {
        $query = Tarifa::find()
            ->alias('t')
            ->joinWith(['apartamento a', 'apartamento.tipoApartamento ta', 'apartamento.ciudad c']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['tipoApartamentoNombre'] = [
            'asc' => ['ta.nombre' => SORT_ASC],
            'desc' => ['ta.nombre' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['ciudadNombre'] = [
            'asc' => ['c.nombre' => SORT_ASC],
            'desc' => ['c.nombre' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['tarifaValor'] = [
            'asc' => ['t.valor' => SORT_ASC],
            'desc' => ['t.valor' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['tarifaFechaInicio'] = [
            'asc' => ['t.fecha_inicio' => SORT_ASC],
            'desc' => ['t.fecha_inicio' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['tarifaFechaFin'] = [
            'asc' => ['t.fecha_fin' => SORT_ASC],
            'desc' => ['t.fecha_fin' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['a.id' => $this->id])
              ->andFilterWhere(['like', 'a.nombre', $this->nombre])
              ->andFilterWhere(['like', 'a.direccion', $this->direccion])
              ->andFilterWhere(['like', 'ta.nombre', $this->tipoApartamentoNombre])
              ->andFilterWhere(['like', 'c.nombre', $this->ciudadNombre])
              ->andFilterWhere(['t.valor' => $this->tarifaValor])
              ->andFilterWhere(['>=', 't.fecha_inicio', $this->tarifaFechaInicio])
              ->andFilterWhere(['<=', 't.fecha_fin', $this->tarifaFechaFin]);

        return $dataProvider;
    }

}
