<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ciudad}}`.
 */
class m240718_081049_create_ciudad_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('ciudad', [
            'id' => $this->primaryKey(),
            'nombre' => $this->string()->notNull(),
        ]);
    
        // Insertar datos predeterminados
        $this->batchInsert('ciudad', ['nombre'], [
            ['Ciudad A'],
            ['Ciudad B'],
            ['Ciudad C'],
        ]);
    }
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('ciudad');
    }
}
