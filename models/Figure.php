<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "figure".
 *
 * @property int $id
 * @property string $side
 * @property string $coordinates
 * @property int $game_id
 *
 * @property Game $game
 */
class Figure extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'figure';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['side', 'coordinates'], 'required'],
            [['game_id'], 'default', 'value' => null],
            [['game_id'], 'integer'],
            [['side', 'coordinates'], 'string', 'max' => 255],
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
        return $this->hasOne(Game::className(), ['id' => 'game_id'])->inverseOf('decks');
    }
}
