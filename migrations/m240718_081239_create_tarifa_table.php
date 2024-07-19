<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tarifa}}`.
 */
class m240718_081239_create_tarifa_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('tarifa', [
            'id' => $this->primaryKey(),
            'apartamento_id' => $this->integer()->notNull(),
            'tipo_tarifa_id' => $this->integer()->notNull(),
            'fecha_inicio' => $this->date()->notNull(),
            'fecha_fin' => $this->date()->notNull(),
            'valor' => $this->decimal(10, 2)->notNull(),
        ]);

        $this->addForeignKey(
            'fk-tarifa-apartamento_id',
            'tarifa',
            'apartamento_id',
            'apartamento',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-tarifa-tipo_tarifa_id',
            'tarifa',
            'tipo_tarifa_id',
            'tipo_tarifa',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-tarifa-apartamento_id', 'tarifa');
        $this->dropForeignKey('fk-tarifa-tipo_tarifa_id', 'tarifa');
        $this->dropTable('tarifa');
    }
}
