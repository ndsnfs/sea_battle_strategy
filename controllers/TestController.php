<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\Test;

/**
 * GameController implements the CRUD actions for Game model.
 */
class TestController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Creates a new Game model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Test();
        $i = Test::find()->all();

        echo '<pre>';
        print_r( $i);
        echo '</pre>';
        exit;

        // if ($model->load(Yii::$app->request->post()) && $model->save()) {

        // }

        return $this->render('index', [

        ]);
    }
}
