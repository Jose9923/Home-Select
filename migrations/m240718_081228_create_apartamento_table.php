<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%apartamento}}`.
 */
class m240718_081228_create_apartamento_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('apartamento', [
            'id' => $this->primaryKey(),
            'nombre' => $this->string()->notNull(),
            'direccion' => $this->string()->notNull(),
            'tipo_apartamento_id' => $this->integer()->notNull(),
            'ciudad_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-apartamento-tipo_apartamento_id',
            'apartamento',
            'tipo_apartamento_id',
            'tipo_apartamento',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-apartamento-ciudad_id',
            'apartamento',
            'ciudad_id',
            'ciudad',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-apartamento-tipo_apartamento_id', 'apartamento');
        $this->dropForeignKey('fk-apartamento-ciudad_id', 'apartamento');
        $this->dropTable('apartamento');
    }
}
