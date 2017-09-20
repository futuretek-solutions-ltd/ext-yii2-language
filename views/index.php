<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('fts-yii2-language', 'Languages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="language-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'locale',
            'lang_code',
            'lang_name',
            'region_code',
            'region_name',
            [
                'attribute' => 'active',
                'format' => 'raw',
                'value' => function ($model) {
                    /** @var \futuretek\language\models\Language $model */
                    return Html::tag('span', Yii::$app->formatter->asBoolean($model->active), ['class' => $model->active ? 'text-success' : 'text-danger']);
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{active}',
                'buttons' => [
                    'active' => function ($url, $model) {
                        /** @var \futuretek\language\models\Language $model */
                        return Html::a(
                            $model->active ? Yii::t('fts-yii2-language', 'Deactivate') : Yii::t('fts-yii2-language', 'Activate'),
                            ['index', 'id' => $model->getPrimaryKey(), 'active' => $model->active ? 0 : 1]
                        );
                    },
                ],
            ],
        ],
    ]); ?>
</div>
