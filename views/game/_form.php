<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Game */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="game-form">

    <?php $form = ActiveForm::begin();

    $authors = \app\models\User::find()->all();
    $items = \yii\helpers\ArrayHelper::map($authors,'id','firstName');
    $params = [
    'prompt' => 'Укажите игрока'
    ];

    $typesGame = ['classic' => 'classic', 'sapper' => 'sapper'];

    echo $form->field($model, 'left_gamer')->dropDownList($items, $params);

    echo $form->field($model, 'right_gamer')->dropDownList($items, $params);

    echo $form->field($model, 'type')->dropDownList($typesGame, ['prompt' => 'Выберите тип игры']); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
