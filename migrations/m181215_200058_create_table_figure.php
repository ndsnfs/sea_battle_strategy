<?php

use yii\db\Migration;

/**
 * Class m181215_200058_create_table_figure
 */
class m181215_200058_create_table_figure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('figure', [
            'id' => $this->primaryKey(),
            'side' => $this->string()->notNull(),
            'coordinates' => $this->string()->notNull(),
            'game_id' => $this->integer(),
        ]);
        $this->addForeignKey('fk_game_figure', 'figure', 'game_id', 'game', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('figure');
    }
}
