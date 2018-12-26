<?php

use yii\db\Migration;

/**
 * Class m181216_212656_create_table_step
 */
class m181216_212656_create_table_step extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('step', [
            'id' => $this->primaryKey(),
            'result' =>$this->string()->notNull(),
            'side' => $this->string()->notNull(),
            'coordinates' => $this->string()->notNull(),
            'game_id' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('fk_game_step', 'step', 'game_id', 'game', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('step');
    }
}
