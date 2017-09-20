<?php

namespace futuretek\language;

use futuretek\language\models\Language;
use yii\base\Action;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * Class IndexAction
 *
 * @package futuretek\language
 * @author  Lukas Cerny <lukas.cerny@futuretek.cz>
 * @license Apache-2.0
 * @link    http://www.futuretek.cz
 */
class IndexAction extends Action
{
    /**
     * @inheritdoc
     * @throws \yii\base\InvalidParamException
     * @throws \yii\web\NotFoundHttpException
     */
    public function run()
    {
        $id = \Yii::$app->request->get('id');
        $active = \Yii::$app->request->get('active');
        if ($id && $active !== null) {
            /** @var Language $model */
            $model = Language::findOne($id);
            if ($model === null) {
                throw new NotFoundHttpException(\Yii::t('fts-yii2-language', 'Language with ID {id} not found.', ['id' => $id]));
            }
            $model->active = (int)$active;
            if (!$model->save()) {
                throw new NotFoundHttpException(\Yii::t('fts-yii2-language', 'Error while updating language.'));
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Language::find()->orderBy(['locale' => SORT_ASC]),
            'pagination' => false,
        ]);

        return $this->controller->render('@vendor/futuretek/yii2-language/views/index', [
            'dataProvider' => $dataProvider,
        ]);
    }
}