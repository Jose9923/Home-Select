<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tipo_pago}}`.
 */
class m240718_081326_create_tipo_pago_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('tipo_pago', [
            'id' => $this->primaryKey(),
            'nombre' => $this->string()->notNull(),
        ]);
        
        // Insertar datos predeterminados
        $this->batchInsert('tipo_pago', ['nombre'], [
            ['Alquiler'],
            ['Tasa de Servicio'],
            ['Tasa de Reserva'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('tipo_pago');
    }
}
