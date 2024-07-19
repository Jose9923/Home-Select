<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tipo_tarifa}}`.
 */
class m240718_081150_create_tipo_tarifa_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('tipo_tarifa', [
            'id' => $this->primaryKey(),
            'nombre' => $this->string()->notNull(),
        ]);
        
        // Insertar datos predeterminados
        $this->batchInsert('tipo_tarifa', ['nombre'], [
            ['Mensual'],
            ['Diaria'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('tipo_tarifa');
    }
}
