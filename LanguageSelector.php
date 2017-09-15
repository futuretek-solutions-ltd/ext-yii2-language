<?php

namespace futuretek\language;

use futuretek\language\assets\FlagIconAsset;
use futuretek\language\models\Language;
use Yii;
use yii\helpers\Html;

/**
 * Class LanguageSelector
 *
 * @package futuretek\language
 * @author  Lukas Cerny <lukas.cerny@futuretek.cz>
 * @license Apache-2.0
 * @link    http://www.futuretek.cz
 */
class LanguageSelector
{
    /**
     * Render drop-down with active languages list
     *
     * @param array $options HTML options @see Html::dropDownList()
     * @return void
     */
    public static function dropDown(array $options = [])
    {
        $items = Language::getLocaleNameList();
        if (count($items) < 2) {
            return;
        }

        echo Html::beginForm(['/fts-language/language/set']);
        echo Html::dropDownList('language', Yii::$app->language, $items, $options);
        echo Html::endForm();
    }

    /**
     * Render list of active language flags
     *
     * @return void
     * @throws \yii\base\InvalidParamException
     */
    public static function flagList()
    {
        $items = Language::getLocaleNameList();
        if (count($items) < 2) {
            return;
        }

        FlagIconAsset::register(Yii::$app->getView());

        $output = [];
        foreach ($items as $locale => $name) {
            $output[] = Html::a(Html::tag('span', '', ['class' => 'flag-icon flag-icon-' . Language::regionFromLocale($locale)]), ['/fts-language/language/set', 'language' => $locale], ['title' => $name]);
        }

        echo Html::tag('div', implode(' ', $output), ['class' => 'language-list']);
    }
}