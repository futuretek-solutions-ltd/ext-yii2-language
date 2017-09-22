<?php

namespace futuretek\language\models;

use futuretek\yii\shared\DbModel;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "language".
 *
 * @property integer $id
 * @property string $locale
 * @property string $lang_code
 * @property string $lang_name
 * @property string $region_code
 * @property string $region_name
 * @property string $active
 * @property string $created_at
 * @property string $updated_at
 *
 */
class Language extends DbModel
{
    /** @var string */
    public $lang_name_native;
    /** @var string */
    public $region_name_native;

    private static $CACHE_LOCALE_MAP = [__NAMESPACE__, __CLASS__, 'locale_map'];
    private static $CACHE_LANG_CODES = [__NAMESPACE__, __CLASS__, 'lang_codes'];
    private static $CACHE_LOCALE_NAME_LIST = [__NAMESPACE__, __CLASS__, 'locale_name_list'];
    private static $CACHE_ID_NAME_LIST = [__NAMESPACE__, __CLASS__, 'id_name_list'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'language';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['locale', 'lang_code', 'lang_name', 'region_code', 'region_name', 'active'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['lang_name', 'region_name'], 'string', 'max' => 64],
            [['lang_code', 'region_code'], 'string', 'max' => 3],
            [['locale'], 'string', 'max' => 8],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('fts-yii2-language', 'ID'),
            'locale' => Yii::t('fts-yii2-language', 'Locale'),
            'lang_code' => Yii::t('fts-yii2-language', 'Language ISO Code'),
            'lang_name' => Yii::t('fts-yii2-language', 'Language Name'),
            'region_code' => Yii::t('fts-yii2-language', 'Region ISO Code'),
            'region_name' => Yii::t('fts-yii2-language', 'Region Name'),
            'active' => Yii::t('fts-yii2-language', 'Active'),
            'created_at' => Yii::t('fts-yii2-language', 'Created At'),
            'updated_at' => Yii::t('fts-yii2-language', 'Updated At'),
        ];
    }

    /**
     * Get all active language locales
     *
     * @return array
     */
    public static function getLocales()
    {
        return array_unique(array_keys(self::getLocaleIdMappingTable()));
    }

    /**
     * Get language by its locale
     *
     * @param string $locale Language locale
     * @return Language
     */
    public static function getByLocale($locale)
    {
        return self::findOne(['locale' => $locale]);
    }

    /**
     * Get language ID by its locale
     *
     * @param string $locale Language locale
     * @return int|null
     */
    public static function getIdByLocale($locale)
    {
        $lang = self::findOne(['locale' => $locale]);

        return $lang === null ? null : $lang->id;
    }

    /**
     * Get language locale to id map
     *
     * @return array Language map locale => id
     */
    public static function getLocaleIdMappingTable()
    {
        $cache = Yii::$app->cache->get(self::$CACHE_LOCALE_MAP);
        if ($cache === false) {
            $languages = self::find()->select(['id', 'locale'])->where(['active' => true])->asArray()->all();
            $mappedLang = ArrayHelper::map($languages, 'locale', 'id');
            Yii::$app->cache->set(self::$CACHE_LOCALE_MAP, ['languages' => $mappedLang]);

            return $mappedLang;
        }

        return $cache['languages'];
    }

    /**
     * Get language codes list
     *
     * @return array Language code list
     */
    public static function getLanguageCodes()
    {
        $cache = Yii::$app->cache->get(self::$CACHE_LANG_CODES);
        if ($cache === false) {
            $languageCodes = self::find()->select(['lang_code'])->distinct()->where(['active' => true])->asArray()->column();
            Yii::$app->cache->set(self::$CACHE_LANG_CODES, ['language_codes' => $languageCodes]);

            return $languageCodes;
        }

        return $cache['language_codes'];
    }

    /**
     * Get language id to locale map
     *
     * @return array Language map id => locale
     */
    public static function getIdCodeMappingTable()
    {
        return array_flip(self::getLocaleIdMappingTable());
    }

    /**
     * Get language list in format locale => name
     *
     * @return array
     */
    public static function getLocaleNameList()
    {
        $cache = Yii::$app->cache->get(self::$CACHE_LOCALE_NAME_LIST);
        if ($cache === false) {
            $mappedLang = [];
            /** @var Language[] $languages */
            $languages = self::find()->where(['active' => true])->orderBy(['lang_name' => SORT_ASC])->all();
            foreach ($languages as $lang) {
                $mappedLang[$lang->locale] = $lang->lang_name . ' (' . $lang->region_name . ') / ' . $lang->lang_name_native . ' (' . $lang->region_name_native . ')';
            }

            Yii::$app->cache->set(self::$CACHE_LOCALE_NAME_LIST, ['languages' => $mappedLang]);

            return $mappedLang;
        }

        return $cache['languages'];
    }

    /**
     * Get language list in format id => name
     *
     * @return array
     */
    public static function getIdNameList()
    {
        $cache = Yii::$app->cache->get(self::$CACHE_ID_NAME_LIST);
        if ($cache === false) {
            $mappedLang = [];
            /** @var Language[] $languages */
            $languages = self::find()->where(['active' => true])->orderBy(['lang_name' => SORT_ASC])->all();
            foreach ($languages as $lang) {
                $mappedLang[$lang->id] = $lang->lang_name . ' (' . $lang->region_name . ') / ' . $lang->lang_name_native . ' (' . $lang->region_name_native . ')';
            }

            Yii::$app->cache->set(self::$CACHE_ID_NAME_LIST, ['languages' => $mappedLang]);

            return $mappedLang;
        }

        return $cache['languages'];
    }

    /**
     * Transform locale (en-US) to language ISO code (en)
     *
     * @param string $locale Locale
     * @return string Language ISO code
     */
    public static function langFromLocale($locale)
    {
        $lParts = explode('-', strtolower(str_replace('_', '-', $locale)));

        return $lParts[0];
    }

    /**
     * Transform locale (en-US) to region ISO code (us)
     *
     * @param string $locale Locale
     * @return string Region ISO code
     * @throws \yii\base\InvalidParamException
     */
    public static function regionFromLocale($locale)
    {
        $lParts = explode('-', str_replace('_', '-', $locale));

        if (!array_key_exists(1, $lParts)) {
            throw new InvalidParamException(Yii::t('app', 'Cannot determine region from locale {locale}.', ['locale' => $locale]));
        }

        return strtolower($lParts[1]);
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        Yii::$app->cache->delete(self::$CACHE_LOCALE_MAP);
        Yii::$app->cache->delete(self::$CACHE_LOCALE_NAME_LIST);
        Yii::$app->cache->delete(self::$CACHE_ID_NAME_LIST);
        Yii::$app->cache->delete(self::$CACHE_LANG_CODES);

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        $this->lang_name_native = \Locale::getDisplayLanguage($this->locale, Yii::$app->language);
        $this->region_name_native = \Locale::getDisplayRegion($this->locale, Yii::$app->language);

        parent::afterFind();
    }
}
