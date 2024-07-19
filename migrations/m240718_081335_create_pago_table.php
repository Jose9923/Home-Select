<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pago}}`.
 */
class m240718_081335_create_pago_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('pago', [
            'id' => $this->primaryKey(),
            'reserva_id' => $this->integer()->notNull(),
            'tipo_pago_id' => $this->integer()->notNull(),
            'valor' => $this->decimal(10, 2)->notNull(),
        ]);

        $this->addForeignKey(
            'fk-pago-reserva_id',
            'pago',
            'reserva_id',
            'reserva',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-pago-tipo_pago_id',
            'pago',
            'tipo_pago_id',
            'tipo_pago',
            'id',
            'CASCADE'
        );
    }
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-pago-reserva_id', 'pago');
        $this->dropForeignKey('fk-pago-tipo_pago_id', 'pago');
        $this->dropTable('pago');
    }
}
