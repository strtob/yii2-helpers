<?php

namespace strtob\yii2helpers;

use Yii;
use \yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use kartik\select2\Select2;

/**
 * The HtmlHelper class provides various utility functions for generating HTML elements and components.
 */
class HtmlHelper
{
    /**
     * Generates a status icon based on the provided state.
     *
     * @param bool $state The state to determine the icon (true for success, false for danger).
     * @param string $css_class_true The CSS class to apply for a true state (default: 'text-success').
     * @param string $css_class_false The CSS class to apply for a false state (default: 'text-danger').
     * @return string The generated HTML for the status icon.
     */
    public static function status(bool $state = true, $css_class_true = 'text-success', $css_class_false = 'text-danger')
    {
        $r = '';

        if ($state)
            $r = '<i class="fa-regular fa-square-check text-success fa-xl ' . $css_class_true . '"></i>';
        else
            $r = '<i class="fa-regular fa-square fa-lg fa-xl ' . $css_class_false . '"></i>';

        return $r;
    }

    /**
     * Retrieves a greeting based on the current hour.
     *
     * @param int|null $hour The hour of the day (0-23), defaults to current hour if null.
     * @return string The greeting message.
     */
    public static function getGreeting($hour = null)
    {
        if (is_null($hour))
            $hour = date('H');

        if ($hour >= 5 && $hour < 12) {
            return Yii::t('app', 'Good morning');
        } elseif ($hour >= 12 && $hour < 15) {
            return Yii::t('app', 'Good afternoon');
        } else {
            return Yii::t('app', 'Good evening');
        }
    }

    /**
     * Generates a translation label with associated elements for translating text.
     *
     * @param string $label The label for the translation.
     * @param string $sourceTextareaSelector The jQuery selector for the source textarea.
     * @param string $targetTextareaSelector The jQuery selector for the target textarea.
     * @param string $targetLang The target language for translation (default: 'en-GB').
     * @param string|null $sourceLang The source language for translation (optional).
     * @return string The generated HTML for the translation label.
     */
    public static function translationLabel($label, $sourceTextareaSelector, $targetTextareaSelector, $targetLang = 'en-GB', $sourceLang = null)
    {
        if (is_null($sourceLang))
            $sourceLang = '';

        $r = '';

        $r .= Yii::t('app', 'Resolution Text') . ' ' .
            Html::img('/images/flags/gb.png') . ' ';

        $r .= '<span class="translateText btn btn-sm btn-info py-0 px-1" ' .
            'data-translate-source-element="' . $sourceTextareaSelector . '" ' .
            'data-translate-target-element="' . $targetTextareaSelector . '" ' .
            'data-translate-lang-from="' . $sourceLang . '" ' .
            'data-translate-lang-to="' . $targetLang . '">' .
            yii::t('app', 'Translate into English') . '</span>';

        return $r;
    }

    /**
     * Generates a section header with an optional icon.
     *
     * @param string $title The title of the section.
     * @param string|null $iconClass The CSS class for the icon (optional).
     * @param bool $first Indicates if this is the first section (default: false).
     * @param string|null $cardheaderClass Additional CSS class for the card header (optional).
     * @return string The generated HTML for the section header.
     */
    public static function sectionHeader($title, $iconClass = null, $first = false, $cardheaderClass = null)
    {
        if (is_null($cardheaderClass))
            if ($first)
                $cardheaderClass = 'mt-0 mb-1 ps-1 pb-2';
            else
                $cardheaderClass = 'mt-2 mb-1 ps-1 pb-2';

        $r = '';

        $r .= '<div class="card-header ' . $cardheaderClass . '">' .
            '<h3 class="card-title mb-0 text-uppercase text-primary">';

        if (!is_null($iconClass))
            $r .= '<i class="' . $iconClass . ' me-2"></i>';

        $r .= $title;

        $r .= '</h3>';
        $r .= '</div>';

        return $r;
    }

    /**
     * Generates an info icon with a tooltip.
     *
     * @param string $text The tooltip text.
     * @param string $cssClass The CSS class for the icon (default: 'text-primary').
     * @return string The generated HTML for the info icon.
     */
    public static function infoIcon($text, $cssClass = 'text-primary')
    {
        $r = '<i class="fa-solid fa-circle-info ' . $cssClass . '" style="cursor: pointer" title="' . $text . '"></i>';
        return $r;
    }

    /**
     * Generates a text icon with a tooltip.
     *
     * @param string $text The tooltip text.
     * @param string $cssClass The CSS class for the icon (default: 'text-primary').
     * @return string The generated HTML for the text icon.
     */
    public static function infoTextIcon($text, $cssClass = 'text-primary')
    {
        $r = '<i class="fa-regular fa-file-lines ' . $cssClass . '" style="cursor: pointer" title="' . $text . '"></i>';
        return $r;
    }

    /**
     * Generates a text input for opening the lookup master table as a modal.
     *
     * @param object $model The model associated with the input.
     * @param int $tbl_entity_master_data_type_id The master data type ID.
     * @param string|null $pjaxContainerRefresh The ID of the PJAX container to refresh (optional).
     * @return string The generated HTML for the text input.
     */
    public static function textInputLookupMasterTable($model, $tbl_entity_master_data_type_id, $pjaxContainerRefresh)
    {
        $r = '';

        $modelType = \backend\models\entity\EntityMasterDataType::findOne($tbl_entity_master_data_type_id);

        $label = $modelType->name;

        $modelMasterData = $model->getLatestMasterDataModel($tbl_entity_master_data_type_id, $pjaxContainerRefresh);

        $value = ($modelMasterData) ? $modelMasterData->getValue() : \yii::t('app', '(to be defined)');

        $r .= '<label class="control-label showModal"'
            . ' style="cursor: pointer;" title="' . Yii::t('app', 'Update Entry') . ' ' . $label . '"'
            . ' data-url="' . \yii\helpers\Url::to(
                    (($modelMasterData) ? ['update-entity-master-data', 'id' => $modelMasterData->id] : ['create-entity-master-data', 'id' => $tbl_entity_master_data_type_id, 'typeId' => $tbl_entity_master_data_type_id])
                    ,
                    true
                ) . '"'
            . ' data-bs-target="#modal-small"'
            . ' data-target-title="<i class=\'ri-pencil-fill align-bottom\'></i> ' . Yii::t('app', 'Update') . ' ' . $label . '"';

        if (!is_null($pjaxContainerRefresh))
            $r .= ' data-ajaxcontainer="' . $pjaxContainerRefresh . '"';

        $r .= '">' . $label;

        $r .= ' ';

        $r .= '<i class="fa-solid fa-pen-to-square text-primary"></i>';

        $r .= '</label>';

        $r .= Html::textInput(
            'attribute_name',
            $value,
            [
                'readonly' => true,
                'disabled' => true,
                'class' => 'form-control',
            ]
        );

        return $r;
    }

    /**
     * Retrieves the flag image URL based on the language ID.
     *
     * @param int $language_id The ID of the language.
     * @return string The flag image URL.
     * @throws \yii\base\Exception if the language is not found.
     */
    public static function getFlagByLanguageId(string $language_id): string
    {
        // Retrieve the language model based on the ID
        $languageModel = \lajax\translatemanager\models\Language::find()
            ->andWhere(['language_id' => $language_id])
            ->one();

        // Get the flag image URL based on the language name
        if ($languageModel)
            return self::getFlagByLanguage($languageModel->language);
        else
            throw new \yii\base\Exception('Nothing found for language_id ' . $language_id);
    }

    /**
     * Retrieves the flag image URL based on the language name.
     *
     * @param string $language The name of the language.
     * @param string $class The CSS class for the image element (optional).
     * @return string The flag image URL.
     */
    public static function getFlagByLanguage(string $language, string $class = ''): string
    {
        // Generate and return the flag image HTML with the specified language and class
        return \yii\bootstrap5\Html::img('/images/flags/' . $language . '.png', ['class' => $class]);
    }

    /**
     * Generates a badge HTML element.
     *
     * @param string $title The text content of the badge.
     * @param string|null $cssClass The badge type (e.g., primary, success) (optional).
     * @return string The generated badge HTML.
     */
    public static function badge(string $title, string $cssClass = null): string
    {
        if (is_null($cssClass))
            $cssClass = 'primary';

        // Generate and return the badge HTML with the specified title and CSS class
        $result = '<span class="badge text-bg-' . $cssClass . '">';
        $result .= $title;
        $result .= '</span> ';

        return $result;
    }

    /**
     * Generates a badge HTML element to indicate status.
     *
     * @param bool $status The status to display (true for enabled, false for disabled).
     * @param string $on The CSS class for the enabled state (default: 'success').
     * @param string $off The CSS class for the disabled state (default: 'danger').
     * @return string The generated badge HTML.
     */
    public static function badgeEnableSwitch(bool $status = true, string $on = 'success', $off = 'danger'): string
    {
        // Generate and return the badge HTML with the specified title and CSS class
        $result = '<span class="badge badge-sm text-bg-' . ($status ? $on : $off) . '">';
        $result .= $status ? yii::t('app', 'enabled') : yii::t('app', 'disabled');
        $result .= '</span> ';

        return $result;
    }

    /**
     * Generates a progress bar HTML element.
     *
     * @param float|int $valueNow The current value of the progress bar.
     * @param string $title The title of the progress bar.
     * @param string $bg The background color of the progress bar (default: 'bg-success').
     * @param string $size The size of the progress bar (e.g., 'sm').
     * @param float|int $valueMin The minimum value of the progress bar (default: 0).
     * @param float|int $valueMax The maximum value of the progress bar (default: 100).
     * @return string The generated progress bar HTML.
     */
    public static function progressBar(float|int $valueNow = 0, string $title = '', string $bg = 'bg-success', string $size = 'sm', float|int $valueMin = 0, float|int $valueMax = 100): string
    {
        // Generate and return the progress bar HTML with the specified attributes
        $result = '<div class="progress progress-' . $size . '">';

        $result .= '<div '
            . 'class="progress-bar ' . $bg . '" '
            . 'role="progressbar" '
            . 'style="width: ' . $valueNow . '%" '
            . 'aria-valuenow="' . $valueNow . '" '
            . 'aria-valuemin="' . $valueMin . '" '
            . 'aria-valuemax="' . $valueMax . '" '
            . 'title="' . $title . '">'
            . '</div>';

        $result .= '</div>';

        return $result;
    }

    /**
     * Generates a progress bar HTML element based on file size.
     *
     * @param mixed $fileSize The size of the file in bytes.
     * @return string The generated progress bar HTML.
     */
    public static function progressBarFileSize($fileSize): string
    {
        $sizeMb = ($fileSize / 1048576);

        $progress = 0;

        if ($sizeMb <= 1) {
            $progress = 20;
        } elseif ($sizeMb > 1 && $sizeMb <= 10) {
            $progress = 40;
        } elseif ($sizeMb > 10 && $sizeMb <= 30) {
            $progress = 60;
        } elseif ($sizeMb > 30) {
            $progress = 100;
        }

        return self::progressBar($progress);
    }

    /**
     * Generates an info box with the bootstrap alert-info style.
     *
     * @param string $title Title of the box which will be translated with Yii::t($translateSection, $title).
     * @param array $content Content as an array which will be translated with Yii::t($translateSection, $content[]).
     * @param string $translateSection Section of Yii::t($translateSection, ''), defaults to 'app'.
     * @return string The generated info box HTML.
     */
    public static function infoBox(string $title, array $content, string $translateSection = 'app'): string
    {
        $result = '';

        $result .= '<div class="alert alert-info alert-border-leftfade show" role="alert">';
        $result .= '<div class="mb-1"><i class="fa-solid fa-circle-info me-3 fs-16"></i>';
        $result .= '<strong>' . \yii::t($translateSection, $title) . '</strong></div>';
        $result .= '<ul class="mb-0" style="padding-left: 50px;">';

        foreach ($content as $item) {
            $result .= '<li>' . \yii::t($translateSection, $item) . '</li>';
        }

        $result .= '</ul></div>';

        return $result;
    }

    /**
     * Converts an array to an HTML ordered list.
     *
     * @param array $array The array to convert.
     * @return string The generated HTML ordered list.
     */
    public static function arrayToHtmlOrderedList($array) {
        $html = '<ol>';
        foreach ($array as $item) {
            $html .= '<li>' . htmlspecialchars($item) . '</li>';
        }
        $html .= '</ol>';
        return $html;
    }
}
