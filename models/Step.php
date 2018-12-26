<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "step".
 *
 * @property int $id
 * @property string $result
 * @property string $side
 * @property string $coordinates
 * @property int $game_id
 *
 * @property Game $game
 */
class Step extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'step';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['result', 'side', 'coordinates', 'game_id'], 'required'],
            [['game_id'], 'default', 'value' => null],
            [['game_id'], 'integer'],
            [['result', 'side', 'coordinates'], 'string', 'max' => 255],
            [['game_id'], 'exist', 'skipOnError' => true, 'targetClass' => Game::className(), 'targetAttribute' => ['game_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'result' => 'Result',
            'side' => 'Side',
            'coordinates' => 'Coordinates',
            'game_id' => 'Game ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGame()
    {
        return $this->hasOne(Game::className(), ['id' => 'game_id'])->inverseOf('steps');
    }
}
