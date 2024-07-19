<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%reserva}}`.
 */
class m240718_081315_create_reserva_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('reserva', [
            'id' => $this->primaryKey(),
            'codigo' => $this->string()->notNull()->unique(),
            'apartamento_id' => $this->integer()->notNull(),
            'cliente_id' => $this->integer()->notNull(),
            'fecha_inicio' => $this->date()->notNull(),
            'fecha_fin' => $this->date()->notNull(),
            'estado_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-reserva-apartamento_id',
            'reserva',
            'apartamento_id',
            'apartamento',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-reserva-cliente_id',
            'reserva',
            'cliente_id',
            'cliente',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-reserva-estado_id',
            'reserva',
            'estado_id',
            'estado',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-reserva-apartamento_id', 'reserva');
        $this->dropForeignKey('fk-reserva-cliente_id', 'reserva');
        $this->dropForeignKey('fk-reserva-estado_id', 'reserva');
        $this->dropTable('reserva');
    }
}
