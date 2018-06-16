<?php

namespace futuretek\language;

use futuretek\language\models\Language;
use yii\base\BootstrapInterface;
use yii\base\Module as YiiModule;
use yii\console\Application;

/**
 * Class Module
 *
 * @package futuretek\language
 * @author  Lukas Cerny <lukas.cerny@futuretek.cz>
 * @license Apache-2.0
 * @link    http://www.futuretek.cz
 */
class Module extends YiiModule implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'futuretek\language\controllers';

    /**
     * @inheritdoc
     */
    public $defaultRoute = 'language';

    /**
     * @var string Cookie domain
     */
    public $cookieDomain = '';
    /**
     * @var string Cookie name
     */
    public $cookieName = 'language';
    /**
     * @var int Expire after (in seconds). If set to null, cookie will expire after closing the browser. Default expiry time is one year.
     */
    public $cookieExpire = 31536000;

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if ($app instanceof Application || (defined('YII_ENV_TEST') && YII_ENV_TEST)) {
            return;
        }
        $supportedLanguages = Language::getLocales();

        $preferredLanguage = isset($app->request->cookies['language']) ? (string)$app->request->cookies['language'] : null;
        if (empty($preferredLanguage) || !in_array($preferredLanguage, $supportedLanguages, true)) {
            $preferredLanguage = $app->request->getPreferredLanguage($supportedLanguages);
        }

        $app->language = $preferredLanguage;
    }
}
