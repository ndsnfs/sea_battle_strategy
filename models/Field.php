<?php
/**
 * Created by PhpStorm.
 * User: ravil
 * Date: 15.12.18
 * Time: 22:43
 */

namespace app\models;


use yii\base\BaseObject;

class Field extends BaseObject
{
    const DIMENTION = 10;
    /**
     * Данные для вычисления значения ячеек
     * @var  Figure[]
     */
    public $decks = [];

    /**
     * Данные для вычисления ячеек
     * @var Step[]
     */
    public $steps = [];

    /**
     * @var Cell[]
     */
    protected $cells;

    public function init()
    {
        $decksCoord = array_column($this->decks,'coordinates');
        // todo сделать еще выстрелы - неявное ограничение игры - в одну клетку нельзя два раза стрелять
        $stepCoord = array_column($this->steps,'result', 'coordinates');;
        $this->cells = self::createCellsList($decksCoord , $stepCoord);
    }

    public static function createCellsList($decksCoord = [], $stepCoord = [])
    {
        $cells = [];
        foreach (array_fill(0, self::DIMENTION, 0) as $rowNumber => $row){
            foreach (array_fill(0, self::DIMENTION, 0) as $colNumber => $col){
                $coordinate = $rowNumber.':'.$colNumber;
                if (array_key_exists($coordinate, $stepCoord)) {
                    // сначала смотрим в ударах
                    $state = $stepCoord[$coordinate];
                }elseif (in_array($coordinate, $decksCoord)) {
                    // потом смотрим оставшиеся целыми палубы
                    $state = Cell::DECK_STATE;
                }else{
                    // оставшиеся пустыми
                    $state = Cell::EMPTY_STATE;
                }
                $cells[] = new Cell($state, $coordinate);
            }
        }
        return $cells;
    }

    public function getCells()
    {
        return $this->cells;
    }

    public function getCell($coordinates)
    {
        foreach ($this->cells as $cell){
            if ($cell->getCoordinates() == $coordinates){
                return $cell;
            }
        }
        throw new \Exception('Ячейка с такими координатами не найдена');
    }
}