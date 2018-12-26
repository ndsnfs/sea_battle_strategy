<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Game */

$this->title = 'Заполнение поля игры перед состязанием';
$this->params['breadcrumbs'][] = ['label' => 'Games', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="game-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_hotsitmodal', [
        'user' => $model->getCurrentUser(),
    ]) ?>

    <?= Html::beginForm('?r=game/fill-field&id='.$model->id) ?>
    <div class="center-block">
        <div class="row background-row">
            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6"><div class="top-cover"></div><p class="top-name"> </p></div>
            <div class="col-lg-4 col-md-6 col-sm-8 col-xs-12"><div class="top-cover"></div>
                <p class="top-name">Расставьте фигуры</p>
                <?php

                $data = \app\models\Field::createCellsList();
                $data = array_chunk($data, \app\models\Field::DIMENTION);
                ?>
                <table>
                    <?php
                    foreach ($data as $row){
                        echo "<tr>";
                        foreach ($row as $cell){
                            /** @var $cell \app\models\Cell */
                            echo "<td><input type='checkbox' name='coordinates[][".$cell->getCoordinates()."]'>&nbsp;";
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

