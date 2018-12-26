<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $field app\models\Field */
/* @var $hitResult \app\models\Field */
/* @var $id int */

$this->title = 'Игра';
$this->params['breadcrumbs'][] = ['label' => 'Games', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="game-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_hotsitmodal', [
        'user' => $user,
    ]) ?>

    <?= Html::beginForm('?r=game/start&id='.$id) ?>
    <div class="center-block">
        <div class="row background-row">
            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6"><div class="top-cover"></div><p class="top-name"> </p></div>
            <div class="col-lg-4 col-md-6 col-sm-8 col-xs-12"><div class="top-cover"></div>
                <p class="top-name">Расставьте фигуры</p>
                <?php
                    $data = array_chunk($field->getCells(), \app\models\Field::DIMENTION);
                ?>
                <table>
                    <?php
                    foreach ($data as $row){
                        echo "<tr>";
                        foreach ($row as $cell){
                            /** @var $cell \app\models\Cell */
                            switch ($cell->getState()){
                                case \app\models\Cell::EMPTY_STATE:
                                case \app\models\Cell::DECK_STATE:
                                    echo "<td bgcolor='aqua'><input type='checkbox' name='step[".$cell->getCoordinates()."]'>&nbsp;";
                                    break;
                                case \app\models\Cell::HIT_STATE:
                                    echo "<td bgcolor='#a52a2a'>&nbsp;</td>";
                                    break;
                                case \app\models\Cell::MISS_STATE:
                                    echo "<td bgcolor='#00008b'>&nbsp;</td>";
                                    break;
                            }
                        }
                        echo "</tr>";
                    }
                    ?>
                </table>
                <?= Html::submitButton('Отправить') ?>
                <?= Html::endForm() ?>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6"><div class="top-cover"></div><p class="top-name"> </p></div>

        </div>
    </div>

</div>

