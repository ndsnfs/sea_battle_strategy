<?php

namespace app\models;

use Yii;

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
class Game extends \yii\db\ActiveRecord
{
    const SIDE_LEFT = 'left';
    const SIDE_RIGHT = 'right';

    const CLASSIC_STRATEGY = 'classic';
    const SAPPER_STRATEGY = 'sapper';

    private $gameBehavior = NULL;

    /**
     * Масив, где ключи - сторогы а значения экземпляры класса Field
     * @var $fields array
     */
    protected $fields;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'game';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['left_gamer', 'right_gamer', 'type'], 'required'],
            [['left_gamer', 'right_gamer'], 'default', 'value' => null],
            [['left_gamer', 'right_gamer'], 'integer'],
            [['attack_side'], 'default', 'value' => self::getFirstStepSide()],
            [['attack_side'], 'string', 'max' => 255],
            [['left_gamer'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['left_gamer' => 'id']],
            [['right_gamer'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['right_gamer' => 'id']],
            [['type'], 'string', 'max' => 255],
        ];
    }

    public function afterFind()
    {
        switch($this->type)
        {
            case self::CLASSIC_STRATEGY:
                $this->gameBehavior = new ClassicGameStrategy($this);
                break;
            case self::SAPPER_STRATEGY:
                $this->gameBehavior = new SapperGameStrategy($this);
                break;

        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'left_gamer' => 'Left Gamer',
            'right_gamer' => 'Right Gamer',
            'attack_side' => 'Attack Side',
            'type' => 'Type',
        ];
    }

    /**
     * возвращает все стороны игры
     * @return array
     */
    public static function getSides()
    {
        return [ self::SIDE_LEFT , self::SIDE_RIGHT ];
    }

    public static function getFirstStepSide()
    {
        return self::SIDE_LEFT;
    }

    /**
     * Отдает пользователя делающего текущий ход
     */
    public function getCurrentUser() : User
    {
        switch ($this->attack_side){
            case self::SIDE_RIGHT;
            return $this->rightGamer;
            case self::SIDE_LEFT;
            return $this->leftGamer;
        }
    }

    /**
     * Отдает текущее поле, по которому палит текущий пользователь
     */
    public function getAttackedField()
    {
        return $this->getField($this->getAttackedSide());
    }

    /**
     * Отдает название стороны, по которой палит текущий пользователь
     */
    public function getAttackedSide()
    {
        switch ($this->attack_side){
            case self::SIDE_RIGHT;
                return self::SIDE_LEFT;
            case self::SIDE_LEFT;
                return self::SIDE_RIGHT;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeftGamer()
    {
        return $this->hasOne(User::className(), ['id' => 'left_gamer']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRightGamer()
    {
        return $this->hasOne(User::className(), ['id' => 'right_gamer']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDecks()
    {
        return $this->hasMany(Figure::className(), ['game_id' => 'id'])->inverseOf('game');
    }

    public function getLeftDecks()
    {
        return $this->hasMany(Figure::className(), ['game_id' => 'id'])->onCondition(['side'=>self::SIDE_LEFT]);
    }

    public function getRightDecks()
    {
        return $this->hasMany(Figure::className(), ['game_id' => 'id'])->onCondition(['side'=>self::SIDE_RIGHT]);
    }

    public function getSideDecks($side)
    {
        switch ($side){
            case self::SIDE_RIGHT;
                return $this->rightDecks;
            case self::SIDE_LEFT;
                return $this->leftDecks;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSteps()
    {
        return $this->hasMany(Step::className(), ['game_id' => 'id'])->inverseOf('game');
    }

    public function getLeftSteps()
    {
        return $this->hasMany(Step::className(), ['game_id' => 'id'])->onCondition(['side'=>self::SIDE_LEFT]);
    }

    public function getRightSteps()
    {
        return $this->hasMany(Step::className(), ['game_id' => 'id'])->onCondition(['side'=>self::SIDE_RIGHT]);
    }

    public function getSideSteps($side)
    {
        switch ($side){
            case self::SIDE_RIGHT;
                return $this->rightSteps;
            case self::SIDE_LEFT;
                return $this->leftSteps;
        }
    }

    public function getField($side) : Field
    {
        $t = NULL;
        if ($this->fields[$side] ?? $t instanceof Field) {
            return $this->fields[$side];
        }

        // если мы раньше не обращались к полю - его надо сформировать из палуб и прошлых выстрелов
        $this->fields[$side] = new Field(['decks' => $this->getSideDecks($side), 'steps' => $this->getSideSteps($side)]);
        return $this->fields[$side];
    }

    /**
     * Нуждается ли игра в заполении (с какой стороны)
     * @return string|false
     */
    public function isNeedToFillBySide()
    {
        if (!$this->leftDecks) {
            return self::SIDE_LEFT;
        }
        if (!$this->rightDecks){
            return self::SIDE_RIGHT;
        }
        return false;
    }

    public function getNext()
    {
        return ($this->attack_side == self::SIDE_LEFT) ? self::SIDE_RIGHT : self::SIDE_LEFT;
    }

    public function fillSide($data){
        $side = $this->isNeedToFillBySide();
        foreach ($data as $coordinates){
            $coordinates = array_values(array_flip($coordinates));
            $deck =  new Figure();
            $deck->coordinates = array_shift($coordinates);
            $deck->side = $side;
            $deck->game_id = $this->id;
            $deck->save();
        }
        $this->attack_side = $this->getNext();
        $this->save();
        $this->refresh();
    }

    /**
     *
     * @param $data  - входящие даные
     */
    public function step($data){
        
        $this->gameBehavior->step($data);
    }
}
