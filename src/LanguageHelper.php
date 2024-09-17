<?php

namespace strtob\yii2helpers;

use Yii;

/**
 * Description of ArHelper
 *
 * @author Tobias Streckel <ts@re-soft.de>
 */
class LanguageHelper
{

    public static function setLanguage($language_id)
    {

        $session = Yii::$app->session;

        if (!is_null(\yii::$app->user->identity)) {

            $session->set('language', $language_id);

        }

        Yii::$app->language = $language_id;
    }

    /**
     * 
     * @return string Return current language, e.g. de which can use for flags.
     */
    public static function getCurrentLanguage(): string
    {
        $language = \backend\models\Language::find()
            ->select('language')
            ->andWhere(['language_id' => Yii::$app->language])
            ->one();

        if ($language === null)
            return 'en-GB';

        return $language->language;
    }
}
