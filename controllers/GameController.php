<?php

namespace app\controllers;

use app\models\User;
use Yii;
use app\models\Game;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GameController implements the CRUD actions for Game model.
 */
class GameController extends Controller
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
     * Lists all Game models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Game::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Game model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Game();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('?r=game/index&id'.$model->id);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function actionStart($id)
    {
        $game = Game::findOne($id);

        if (!$game) {
            throw new NotFoundHttpException('игра не найдена');
        }

        if ($game->isNeedToFillBySide()) {
            return $this->redirect('?r=game/fill-field&id='.$game->id);
        }

        $data = Yii::$app->request->post('step');


        if ($data) {
            $game->step($data);
        }

        // todo здесь надо сделать не произошло ли победы одного из игроков

        return $this->render('game', [
            'field' => $game->getAttackedField(),
            'user' => $game->getCurrentUser(),
            'id' => $game->id,
        ]);
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionFillField($id)
    {
        $data= Yii::$app->request->post('coordinates');

        $game = Game::findOne($id);
        if (!$game) {
            throw new NotFoundHttpException('игра не найдена');
        }

        // нас редиректнули сюда значит нуно отобразить поле для заполнения
        if (!$data) {
            return $this->render('fill', [
                'model' => $game,
            ]);
        }

        // если пришли данные нужно отдать их в игру
        if ($game->isNeedToFillBySide() && $data) {
            // todo сделать валидацию входных данных
            $game->fillSide($data);
        }

        // если еще надо заполнить что-то идем на второй круг
        if ($game->isNeedToFillBySide()) {
            return $this->redirect('?r=game/fill-field&id='.$game->id);
        }

        return $this->redirect('?r=game/start&id='.$game->id);
    }

    /**
     * Finds the Game model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Game the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Game::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
