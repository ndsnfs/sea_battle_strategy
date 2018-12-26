<?php

namespace app\models;

use Yii;
use app\iface\GameStrategy;

/**
 * This is the model class for table "game".
 *
 * @property int $id
 * @property int $left_gamer
 * @property int $right_gamer
 * @property string $attack_side
 * @property string $type
 *
 * @property User $leftGamer
 * @property User $rightGamer
 */
class SapperGameStrategy extends \yii\db\ActiveRecord implements GameStrategy
{	
	private $game;

	public function __construct(Game $g)
	{
		$this->game = $g;
	}

	public function step($data)
	{
        $k = array_keys($data);
        $coord = array_shift($k);
        $field = $this->game->getAttackedField();

        $newState = $field->getCell($coord)->applyRule(
            [
                Cell::DECK_STATE => Cell::MISS_STATE,
                Cell::EMPTY_STATE => Cell::HIT_STATE
            ]
        );

        //создание нового хода
        $step = new Step();
        $step->coordinates = $coord;
        $step->side = $this->game->getAttackedSide();
        $step->game_id = $this->game->id;
        $step->result = $newState;
        $step->save();

        // если не попал - ход отдается другому игроку
        if (in_array($newState, [Cell::MISS_STATE])) {
            $this->game->attack_side = $this->game->getNext();
            $this->game->save();
        }
	}
}