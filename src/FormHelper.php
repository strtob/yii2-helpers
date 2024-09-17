<?php

namespace strtob\yii2helpers;

use Yii;
use yii\web\View;
use yii\web\JsExpression;
use yii\helpers\Html;
use \backend\models\person\Person;

/**
 * Description of FormHelper
 *
 * @author Tobias Streckel <ts@re-soft.de>
 */
class FormHelper
{

    public static function badgeSelect2Ajax($view, $form, $model, $attribute, $lookupServiceAction = '/lookup-service/')
    {

        $id = Html::getInputId($model, $attribute) . '_' . uniqid();

        $r = '';

        // register js files for select2 below
        select2Templates\_badge\select2TemplateAsset::register($view);

        $r .= $form->field($model, $attribute)->widget(\kartik\widgets\Select2::className(), [
            'options' => [
                'placeholder' => Yii::t('app', 'Choose...'),
                'id' => $id,
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 0,
                'language' => [
                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                ],
                'ajax' => [
                    'url' => \yii\helpers\Url::to([$lookupServiceAction]),
                    'dataType' => 'JSON',
                    'data' => new JsExpression('function(params) { return {q:params.term, page: params.page}; }'),
                    'processResults' => new JsExpression('parseBadgeResults'),
                    'cache' => !YII_DEBUG
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('formatBadge'),
                'templateSelection' => new JsExpression('formatBadgeSelection'),
            ],
        ]);

        return $r;
    }

    public static function userSelect2Ajax($view, $form, $model, $attribute = 'tbl_user_id', $id = null, $label = null)
    {

        if (is_null($id))
            $id = Html::getInputId($model, $attribute) . '_' . uniqid();

        if (is_null($label))
            $label = \yii::t('app', 'User');

        $labelAddOn = '';

        //        if (!$model->isNewRecord)
//            $labelAddOn = '<i class="fa-solid fa-arrow-up-right-from-square followLinkValue" data-value="'
//                    . \yii\helpers\Url::to(['/entity/update', 'id' => $model->$attribute])
//                    . '" data-newtab="true" style="cursor: pointer" title="' . yii::t('app', 'Open entry in new tab.') . '"></i>';
//
//        $labelAddOn .= '<i class="ms-2 fa-solid fa-plus followLinkValue" data-value="'
//                . \yii\helpers\Url::to(['/entity/index', 'action' => 'create'])
//                . '" data-newtab="true" style="cursor: pointer" title="' . yii::t('app', 'Open entry in new tab.') . '"></i>';

        $r = '';

        // register js files for select2 below
        select2Templates\user\select2TemplateAsset::register($view);

        $r .= $form->field($model, $attribute)->widget(\kartik\widgets\Select2::className(), [
            'options' => [
                'placeholder' => Yii::t('app', 'Choose...'),
                'id' => $id,
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => YII_DEBUG ? 1 : 3,
                'language' => [
                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                ],
                'ajax' => [
                    'url' => \yii\helpers\Url::to(['/lookup-service/user']),
                    'dataType' => 'JSON',
                    'data' => new JsExpression('function(params) { return {q:params.term, page: params.page}; }'),
                    'processResults' => new JsExpression('parseUserResults'),
                    'cache' => !YII_DEBUG
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('formatUser'),
                'templateSelection' => new JsExpression('formatUserSelection'),
            ],
        ])->label($label . ' ' . $labelAddOn);

        return $r;
    }

    public static function amountInput($form, $model, $attribute, $currencyCode = null, $noCurrecyMessage = null)
    {
        if (is_null($noCurrecyMessage))
            $noCurrecyMessage = yii::t('app', 'Please select country and save to set currency.');

        $currencyCodeInfo = '';

        if (is_null($currencyCode))
            $currencyCodeInfo = ' <i class="fa-solid fa-circle-info" '
                . 'style="cursor:pointer" title="'
                . $noCurrecyMessage
                . '"></i>';

        $r = '';

        $r .= $form->field($model, $attribute, ['template' => '{label}' . $currencyCodeInfo . ' {input} {hint} {error}'])
            ->widget(\kartik\number\NumberControl::classname(), [
                'maskedInputOptions' => [
                    'suffix' => ' ' . ($currencyCode ? $currencyCode : ''),
                    'allowMinus' => false,
                    'digits' => 2,
                    'min' => 0,
                    'max' => 9999999999
                ],
            ]);

        return $r;
    }

    /**
     * Helper function to render a DateRangePicker widget in a form.
     * 
     *  Model Adjustment needed
     * 
     * public function afterFind()
     * {
     *    parent::afterFind();
     *
     *    // Set valid_date_range attribute using the static helper function
     *    $this->valid_date_range = \backend\components\helpers\DateHelper::formatJsDateRange($this->valid_from, $this->valid_until);
     *    $this->contract_date_range = \backend\components\helpers\DateHelper::formatJsDateRange($this->begin, $this->end);
     * }
     *
     * public function beforeSave($insert)
     * {
     *    // Convert date strings to MySQL-compatible format before saving
     *    $this->valid_from  = Yii::$app->formatter->asDate($this->valid_from, 'yyyy-MM-dd');
     *    $this->valid_until = Yii::$app->formatter->asDate($this->valid_until, 'yyyy-MM-dd');
     *
     *    // Convert date strings to MySQL-compatible format before saving
     *    $this->valid_from  = Yii::$app->formatter->asDate($this->begin, 'yyyy-MM-dd');
     *    $this->valid_until = Yii::$app->formatter->asDate($this->end, 'yyyy-MM-dd');
     *    
     *    return parent::beforeSave($insert);
     * }
     *
     * @param ActiveForm $form the form instance
     * @param Model $model the model instance
     * @param string $startAttribute the start date attribute in the model
     * @param string $endAttribute the end date attribute in the model
     * @param string $rangeAttribute the attribute to store the date range in the model
     * @param string|null $label the label for the date range field (optional)
     *
     * @return ActiveField the rendered DateRangePicker field
     */
    public static function dateRange($form, $model, $startAttribute, $endAttribute, $rangeAttribute, $label = null)
    {

        $r = $form->field(
            $model,
            $rangeAttribute,
            [
                'addon' => [
                    'prepend' => [
                        'content' => '<i class="fas fa-calendar-alt"></i></span>',
                    ]
                ],
                'options' => ['class' => 'drp-container mb-2'],
            ]
        )
            ->widget(
                \kartik\daterange\DateRangePicker::class,
                [
                    'useWithAddon' => true,
                    'convertFormat' => true,
                    'startAttribute' => $startAttribute,
                    'endAttribute' => $endAttribute,
                    'startInputOptions' => ['value' => date('Y-m-d')],
                    'pluginOptions' =>
                        [
                            'todayHighlight' => true,
                            'allowClear' => true,
                            'locale' => [
                                'format' => str_replace("php:", "", Yii::$app->formatter->dateFormat),
                            ],
                            'showDropdowns' => true,
                        ],
                ]
            );

        if (!is_null($label))
            $r->label($label);

        return $r;
    }

    public static function entitySelect2Ajax($view, $form, $model, $attribute = 'tbl_entity_id', $id = null, $label = null)
    {

        if (is_null($id))
            $id = Html::getInputId($model, $attribute) . '_' . uniqid();

        if (is_null($label))
            $label = \yii::t('app', 'Entity');

        $labelAddOn = '';

        if (!$model->isNewRecord)
            $labelAddOn = '<i class="fa-solid fa-arrow-up-right-from-square followLinkValue" data-value="'
                . \yii\helpers\Url::to(['/entity/update', 'id' => $model->$attribute])
                . '" data-newtab="true" style="cursor: pointer" title="' . yii::t('app', 'Open entry in new tab.') . '"></i>';

        $labelAddOn .= '<i class="ms-2 fa-solid fa-plus followLinkValue" data-value="'
            . \yii\helpers\Url::to(['/entity/index', 'action' => 'create'])
            . '" data-newtab="true" style="cursor: pointer" title="' . yii::t('app', 'Open entry in new tab.') . '"></i>';

        $r = '';

        // register js files for select2 below
        select2Templates\entity\select2TemplateAsset::register($view);

        $r .= $form->field($model, $attribute)->widget(\kartik\widgets\Select2::className(), [
            'options' => [
                'placeholder' => Yii::t('app', 'Choose...'),
                'id' => $id,
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => YII_DEBUG ? 1 : 3,
                'language' => [
                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                ],
                'ajax' => [
                    'url' => \yii\helpers\Url::to(['/lookup-service/entity']),
                    'dataType' => 'JSON',
                    'data' => new JsExpression('function(params) { return {q:params.term, page: params.page}; }'),
                    'processResults' => new JsExpression('parseEntityResults'),
                    'cache' => !YII_DEBUG
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('formatEntity'),
                'templateSelection' => new JsExpression('formatEntitySelection'),
            ],
        ])->label($label . ' ' . $labelAddOn);

        return $r;
    }

    public static function boardResolutionSelect2Ajax($view, $form, $model, $attribute = 'tbl_entity_board_resolution_id', $id = null, $label = null)
    {

        if (is_null($id))
            $id = Html::getInputId($model, $attribute) . '_' . uniqid();

        if (is_null($label))
            $label = \yii::t('app', 'Board Resolution');

        $labelAddOn = '';

        if (!$model->isNewRecord)
            $labelAddOn = '<i class="fa-solid fa-arrow-up-right-from-square followLinkValue" data-value="'
                . \yii\helpers\Url::to(['/entity-board-resolution/update', 'id' => $model->$attribute])
                . '" data-newtab="true" style="cursor: pointer" title="' . yii::t('app', 'Open entry in new tab.') . '"></i>';

        $labelAddOn .= '<i class="ms-2 fa-solid fa-plus followLinkValue" data-value="'
            . \yii\helpers\Url::to(['/entity-board-resolution/create', 'action' => 'create'])
            . '" data-newtab="true" style="cursor: pointer" title="' . yii::t('app', 'Open entry in new tab.') . '"></i>';

        $r = '';

        // register js files for select2 below
        select2Templates\boardresolution\select2TemplateAsset::register($view);

        $r .= $form->field($model, $attribute)->widget(\kartik\widgets\Select2::className(), [
            'options' => [
                'placeholder' => Yii::t('app', 'Choose...'),
                'id' => $id,
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => YII_DEBUG ? 1 : 3,
                'language' => [
                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                ],
                'ajax' => [
                    'url' => \yii\helpers\Url::to(['/lookup-service/board-resolution']),
                    'dataType' => 'JSON',
                    'data' => new JsExpression('function(params) { return {q:params.term, page: params.page}; }'),
                    'processResults' => new JsExpression('parseBoardresultionResults'),
                    'cache' => !YII_DEBUG
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('formatBoardresolution'),
                'templateSelection' => new JsExpression('formatBoardresolutionSelection'),
            ],
        ])->label($label . ' ' . $labelAddOn);

        return $r;
    }

    public static function countrySelect2Ajax($view, $form, $model, $attribute = 'tbl_basic_geo_country_id', $id = null)
    {

        if (is_null($id))
            $id = Html::getInputId($model, $attribute) . '_' . uniqid();

        $r = '';

        // register js files for select2 below
        select2Templates\country\select2TemplateAsset::register($view);

        $r .= $form->field($model, $attribute)->widget(\kartik\widgets\Select2::className(), [
            'options' => [
                'placeholder' => Yii::t('app', 'Choose...'),
                'id' => $id,
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => YII_DEBUG ? 1 : 3,
                'language' => [
                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                ],
                'ajax' => [
                    'url' => \yii\helpers\Url::to(['/lookup-service/country']),
                    'dataType' => 'JSON',
                    'data' => new JsExpression('function(params) { return {q:params.term, page: params.page}; }'),
                    'processResults' => new JsExpression('parseCountryResults'),
                    'cache' => !YII_DEBUG
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('formatCountry'),
                'templateSelection' => new JsExpression('formatCountrySelection'),
            ],
        ]);

        return $r;
    }

    public static function contractSelect2Ajax($view, $form, $model, $attribute = 'tbl_contract_id', $id = null, $label = null)
    {

        if (is_null($id))
            $id = Html::getInputId($model, $attribute) . '_' . uniqid();

        if (is_null($label))
            $label = \yii::t('app', 'Contract');

        $labelAddOn = '';

        if ($model->isNewRecord)
            $labelAddOn = '<i class="fa-solid fa-arrow-up-right-from-square followLinkValue" data-value="'
                . \yii\helpers\Url::to(['/contract/update', 'id' => $model->$attribute])
                . '" data-newtab="true" style="cursor: pointer" title="' . yii::t('app', 'Open entry in new tab.') . '"></i>';

        $labelAddOn .= '<i class="ms-2 fa-solid fa-plus followLinkValue" data-value="'
            . \yii\helpers\Url::to(['/contract/create', 'action' => 'create'])
            . '" data-newtab="true" style="cursor: pointer" title="' . yii::t('app', 'Open entry in new tab.') . '"></i>';

        $r = '';

        // register js files for select2 below
        select2Templates\contract\select2TemplateAsset::register($view);

        $r .= $form->field($model, $attribute)->widget(\kartik\widgets\Select2::className(), [
            'options' => [
                'placeholder' => Yii::t('app', 'Choose...'),
                'id' => $id,
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => YII_DEBUG ? 1 : 3,
                'language' => [
                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                ],
                'ajax' => [
                    'url' => \yii\helpers\Url::to(['/lookup-service/contract']),
                    'dataType' => 'JSON',
                    'data' => new JsExpression('function(params) { return {q:params.term, page: params.page}; }'),
                    'processResults' => new JsExpression('parseContractResults'),
                    'cache' => !YII_DEBUG
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('formatContract'),
                'templateSelection' => new JsExpression('formatContractSelection'),
            ],
        ])->label($label . ' ' . $labelAddOn);

        return $r;
    }

    public static function citySelect2Ajax($form, $model, $attribute = 'tbl_basic_geo_city_id', $id = null)
    {

        if (is_null($id))
            $id = Html::getInputId($model, $attribute) . '_' . uniqid();

        return $form->field($model, $attribute)->widget(\kartik\widgets\Select2::className(), [
            'initValueText' =>
                isset($model[$attribute]) && $model[$attribute] != '' ? \backend\models\basic\BasicGeoCity::findOne($model[$attribute])->name : '',
            'options' => [
                'placeholder' => Yii::t('app', 'Choose...'),
                'id' => $id,
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 2,
                'language' => [
                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                ],
                'ajax' => [
                    'url' => \yii\helpers\Url::to(['/lookup-service/city']),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }'),
                    'cache' => !YII_DEBUG,
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(city) { return city.text; }'),
                'templateSelection' => new JsExpression('function (city) { return city.text; }'),
            ],
        ])->label('City');
    }


    public static function companyExternalUserSelect2Ajax($view, $form, $model, $attribute = 'tbl_company_external_user_id', $id = null, $label = null)
    {


        if (is_null($id))
            $id = Html::getInputId($model, $attribute) . '_' . uniqid();

        if (is_null($label))
            $label = \yii::t('app', 'External');

        $labelAddOn = '';

        if (!$model->isNewRecord)
            $labelAddOn = '<i class="fa-solid fa-arrow-up-right-from-square followLinkValue" data-value="'
                . \yii\helpers\Url::to(['/company-external-user/update', 'id' => $model->$attribute])
                . '" data-newtab="true" style="cursor: pointer" title="' . yii::t('app', 'Open entry in new tab.') . '"></i>';

        $labelAddOn .= '<i class="ms-2 fa-solid fa-plus followLinkValue" data-value="'
            . \yii\helpers\Url::to(['/company-external-user/index', 'action' => 'create'])
            . '" data-newtab="true" style="cursor: pointer" title="' . yii::t('app', 'Open entry in new tab.') . '"></i>';

        $r = '';

        // register js files for select2 below
        select2Templates\externalUser\select2TemplateAsset::register($view);

        $r .= $form->field($model, $attribute)->widget(\kartik\widgets\Select2::class, [
            'options' => [
                'placeholder' => Yii::t('app', 'Choose...'),
                'id' => $id,
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => YII_DEBUG ? 1 : 3,
                'language' => [
                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                ],
                'ajax' => [
                    'url' => \yii\helpers\Url::to(['/lookup-service/external-user']),
                    'dataType' => 'JSON',
                    'data' => new JsExpression('function(params) { return {q:params.term, page: params.page}; }'),
                    'processResults' => new JsExpression('parseCompanyResults'),
                    'cache' => !YII_DEBUG
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('formatExternalUser'),
                'templateSelection' => new JsExpression('formatExternalUserSelection'),
            ],
        ])->label($label . ' ' . $labelAddOn);

        return $r;
    }

    public static function companySelect2Ajax($view, $form, $model, $attribute = 'tbl_company_id', $id = null, $label = null)
    {


        if (is_null($id))
            $id = Html::getInputId($model, $attribute) . '_' . uniqid();

        if (is_null($label))
            $label = \yii::t('app', 'Company');

        $labelAddOn = '';

        if (!$model->isNewRecord)
            $labelAddOn = '<i class="fa-solid fa-arrow-up-right-from-square followLinkValue" data-value="'
                . \yii\helpers\Url::to(['/company/update', 'id' => $model->$attribute])
                . '" data-newtab="true" style="cursor: pointer" title="' . yii::t('app', 'Open entry in new tab.') . '"></i>';

        $labelAddOn .= '<i class="ms-2 fa-solid fa-plus followLinkValue" data-value="'
            . \yii\helpers\Url::to(['/company/index', 'action' => 'create'])
            . '" data-newtab="true" style="cursor: pointer" title="' . yii::t('app', 'Open entry in new tab.') . '"></i>';

        $r = '';

        // register js files for select2 below
        select2Templates\company\select2TemplateAsset::register($view);

        $r .= $form->field($model, $attribute)->widget(\kartik\widgets\Select2::className(), [
            'options' => [
                'placeholder' => Yii::t('app', 'Choose...'),
                'id' => $id,
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => YII_DEBUG ? 1 : 3,
                'language' => [
                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                ],
                'ajax' => [
                    'url' => \yii\helpers\Url::to(['/lookup-service/company']),
                    'dataType' => 'JSON',
                    'data' => new JsExpression('function(params) { return {q:params.term, page: params.page}; }'),
                    'processResults' => new JsExpression('parseCompanyResults'),
                    'cache' => !YII_DEBUG
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('formatCompany'),
                'templateSelection' => new JsExpression('formatCompanySelection'),
            ],
        ])->label($label . ' ' . $labelAddOn);

        return $r;
    }

    public static function personDepDropAjax($form, $model, $dependsAttributeId, $attribute = 'tbl_company_id', $id = null, $label = null)
    {

        if (is_null($id))
            $id = Html::getInputId($model, $attribute) . '_' . uniqid();

        if (is_null($label))
            $label = \yii::t('app', 'Person');


        $labelAddOn = '';

        if (!$model->isNewRecord)
            $labelAddOn = '<i class="fa-solid fa-arrow-up-right-from-square followLinkValue" data-value="'
                . \yii\helpers\Url::to(['/person/update', 'id' => $model->$attribute])
                . '" data-newtab="true" style="cursor: pointer" title="' . yii::t('app', 'Open entry in new tab.') . '"></i>';

        $labelAddOn .= '<i class="ms-2 fa-solid fa-plus followLinkValue" data-value="'
            . \yii\helpers\Url::to(['/person/create', 'action' => 'create'])
            . '" data-newtab="true" style="cursor: pointer" title="' . yii::t('app', 'Open entry in new tab.') . '"></i>';

        // default value
        $defaultValue = Person::findOne($model->{$attribute});
        if (is_null($defaultValue))
            $defaultValue = '(' . yii::t('app', '') . ')';
        else
            $defaultValue = $defaultValue->fullName;

        return $form->field($model, $attribute)
            ->widget(\kartik\widgets\DepDrop::class, [
                'type' => \kartik\widgets\DepDrop::TYPE_SELECT2,
                'data' => [Html::getAttributeValue($model, $attribute) => $defaultValue],
                'options' => [
                    'prompt' => '(' . Yii::t('app', 'select company...') . ')',
                    'id' => $id,
                    'allowClear' => true,
                ],
                'pluginOptions' => [
                    'depends' => [$dependsAttributeId],
                    'url' => \yii\helpers\Url::to(['/lookup-service/person-dep-drop']),
                    'loadingText' => Yii::t('app', 'Loading') . '...',
                    'initialize' => $model->isNewRecord ? false : true,
                    'initDepends' => [Html::getInputId($model, 'tbl_entity_type_id')],
                ],
                //                            'select2Options' => [
//                                'initValueText' => is_null($model->$attribute) ? '' : $model->$attribute,
//                            ]
            ])->label($label . ' ' . $labelAddOn);
        ;
    }

    public static function personAjax($form, $model, $attribute = 'tbl_company_id', $id = null, $label = null)
    {

        if (is_null($id))
            $id = Html::getInputId($model, $attribute) . '_' . uniqid();

        if (is_null($label))
            $label = \yii::t('app', 'Person');


        $labelAddOn = '';

        if (!$model->isNewRecord)
            $labelAddOn = '<i class="fa-solid fa-arrow-up-right-from-square followLinkValue" data-value="'
                . \yii\helpers\Url::to(['/person/update', 'id' => $model->$attribute])
                . '" data-newtab="true" style="cursor: pointer" title="' . yii::t('app', 'Open entry in new tab.') . '"></i>';

        $labelAddOn .= '<i class="ms-2 fa-solid fa-plus followLinkValue" data-value="'
            . \yii\helpers\Url::to(['/person/create', 'action' => 'create'])
            . '" data-newtab="true" style="cursor: pointer" title="' . yii::t('app', 'Open entry in new tab.') . '"></i>';

        // default value
        $defaultValue = Person::findOne($model->{$attribute});
        if (is_null($defaultValue))
            $defaultValue = yii::t('app', 'Choose...');
        else
            $defaultValue = $defaultValue->fullName;

        return $form->field($model, $attribute)
            ->widget(\kartik\widgets\Select2::class, [
                'data' => [Html::getAttributeValue($model, $attribute) => $defaultValue],
                'options' => [
                    'prompt' => Yii::t('app', 'Choose...'),
                    'id' => $id,
                    'allowClear' => true,
                ],
                'pluginOptions' => [
                    'minimumInputLength' => YII_DEBUG ? 1 : 2,
                    'ajax' => [
                        'url' => \yii\helpers\Url::to(['/lookup-service/person']),
                        'dataType' => 'JSON',
                        'data' => new JsExpression('function(params) { return {q:params.term, page: params.page}; }'),
                        'cache' => !YII_DEBUG
                    ],
                    'escapeMarkup' => new JsExpression('function (data) { return data; }'),
                    'templateResult' => new JsExpression('function(data) { return data.text; }'),
                    'templateSelection' => new JsExpression('function (data) { return data.text; }'),
                ],
            ])->label($label . ' ' . $labelAddOn);
        ;
    }
}
