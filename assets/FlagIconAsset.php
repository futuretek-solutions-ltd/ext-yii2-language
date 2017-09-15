<?php

namespace futuretek\language\assets;

use yii\web\AssetBundle;

/**
 * Class FlagIconAsset
 *
 * @package futuretek\language\assets
 * @author  Lukas Cerny <lukas.cerny@futuretek.cz>
 * @license Apache-2.0
 * @link    http://www.futuretek.cz
 */
class FlagIconAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = '@vendor/futuretek/yii2-language/assets/flags/';
        $this->baseUrl = '@web';

        $this->css = [
            'sass/flag-icon.scss',
        ];

        parent::init();
    }
}
