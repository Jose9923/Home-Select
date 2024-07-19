<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%estado}}`.
 */
class m240718_081200_create_estado_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('estado', [
            'id' => $this->primaryKey(),
            'nombre' => $this->string()->notNull(),
        ]);
        
        // Insertar datos predeterminados
        $this->batchInsert('estado', ['nombre'], [
            ['Activa'],
            ['Anulada'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('estado');
    }
}
