<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \app\models\User */

\yii\bootstrap\Modal::begin([

    'clientOptions' => ['show' => true],
    'size' => 'modal-lg',

]);

echo "Ход игрока " . $user->firstName;



\yii\bootstrap\Modal::end();

?>

