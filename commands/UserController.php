<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\User;
use yii\console\Controller;
use yii\console\ExitCode;

class UserController extends Controller
{
    /**
     * Наполняет базу стандартными пользователями
     */
    public function actionFill()
    {
        foreach ([
                     ['name' => 'Равиль', 'lastName' => 'Шаменов'],
                     ['name' => 'Андрей', 'lastName' => 'Воронцов'],
                     ['name' => 'Николай', 'lastName' => 'Баранов'],
                 ] as $user){
            $userActiveRecord = User::find()->where(['firstName' => $user['name'], 'lastName' => $user['lastName']])->one();
            if (!$userActiveRecord){
                $userActiveRecord = new User();
                $userActiveRecord->lastName =  $user['lastName'];
                $userActiveRecord->firstName = $user['name'];
                $userActiveRecord->save();
                echo "пользователь создан\n";
            }else{
                echo "Пользователь уже есть\n";
            }
        }
    }
}
