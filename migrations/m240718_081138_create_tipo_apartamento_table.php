<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tipo_apartamento}}`.
 */
class m240718_081138_create_tipo_apartamento_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('tipo_apartamento', [
            'id' => $this->primaryKey(),
            'nombre' => $this->string()->notNull(),
        ]);

        // Insertar datos predeterminados
        $this->batchInsert('tipo_apartamento', ['nombre'], [
            ['Corporativo'],
            ['Turístico'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('tipo_apartamento');
    }
}
