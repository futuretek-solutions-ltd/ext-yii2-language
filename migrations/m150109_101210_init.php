<?php

use futuretek\migrations\FtsMigration;
use futuretek\yii\shared\DT;

class m150109_101210_init extends FtsMigration
{
    public function safeUp()
    {
        Yii::$app->db->createCommand()->checkIntegrity(false)->execute();

        if (Yii::$app->db->schema->getTableSchema('language') !== null) {
            $this->dropTable('language');
        }

        $this->createTable(
            'language',
            [
                'id' => $this->primaryKey(),
                'locale' => $this->string(8)->notNull()->unique(),
                'lang_code' => $this->string(3)->notNull(),
                'lang_name' => $this->string(64)->notNull(),
                'region_code' => $this->string(3)->notNull(),
                'region_name' => $this->string(64)->notNull(),
                'active' => $this->boolean()->notNull()->defaultValue(false),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ]
        );

        $f = fopen(__DIR__ . '/locales.txt', 'rb');
        while ($locale = trim(fgets($f))) {
            if (!$locale) {
                continue;
            }
            $lParts = explode('-', $locale);

            $this->insert('language', [
                'locale' => $locale,
                'lang_code' => $lParts[0],
                'lang_name' => Locale::getDisplayLanguage($locale, 'en_US'),
                'region_code' => $lParts[1],
                'region_name' => Locale::getDisplayRegion($locale, 'en_US'),
                'active' => in_array($locale, ['cs-CZ', 'en-GB'], true),
                'created_at' => DT::toDb('now'),
                'updated_at' => DT::toDb('now'),
            ]);
        }
        fclose($f);

        Yii::$app->db->createCommand()->checkIntegrity()->execute();
    }

    public function safeDown()
    {
        Yii::$app->db->createCommand()->checkIntegrity(false)->execute();

        $this->dropTable('language');

        Yii::$app->db->createCommand()->checkIntegrity()->execute();
    }
}
