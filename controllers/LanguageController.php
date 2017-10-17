<?php

namespace futuretek\language\controllers;

use futuretek\language\models\Language;
use futuretek\language\Module;
use Yii;
use yii\base\InvalidCallException;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Cookie;

/**
 * Class LanguageController
 *
 * @package futuretek\language\controllers
 * @author  Lukas Cerny <lukas.cerny@futuretek.cz>
 * @license Apache-2.0
 * @link    http://www.futuretek.cz
 *
 * @property Module $module
 */
class LanguageController extends Controller
{
    public $defaultAction = 'set';

    /**
     * Set language action
     *
     * @throws InvalidCallException
     * @throws BadRequestHttpException
     * @throws \yii\base\InvalidParamException
     */
    public function actionSet()
    {
        $language = Yii::$app->request->post('language', Yii::$app->request->get('language'));
        if (!$language) {
            throw new BadRequestHttpException(Yii::t('fts-yii2-language', 'Language code was not set.'));
        }

        $supportedLocales = Language::getLocales();
        if (!in_array($language, $supportedLocales, true)) {
            throw new BadRequestHttpException(Yii::t('fts-yii2-language', 'Selected language is not active or not exist.'));
        }
        Yii::$app->language = $language;

        $languageCookie = new Cookie([
            'name' => $this->module->cookieName,
            'domain' => $this->module->cookieDomain,
            'value' => $language,
            'expire' => $this->module->cookieExpire === null ? 0 : time() + $this->module->cookieExpire,
        ]);
        Yii::$app->response->cookies->add($languageCookie);

        if (Yii::$app->request->getReferrer() !== null) {
            $backUrl = Yii::$app->request->getReferrer();
        } else {
            $backUrl = Yii::$app->request->post('backUrl', Yii::$app->request->get('backUrl'));
        }

        array_walk($supportedLocales, function (&$value) {
            $value = '/' . $value . '/';
        });

        $backUrl = str_replace($supportedLocales, '/' . $language . '/', $backUrl);

        if ($backUrl) {
            $this->redirect($backUrl);
        } else {
            throw new InvalidParamException(Yii::t('fts-yii2-language', 'No referrer or backUrl set.'));
        }
    }
}