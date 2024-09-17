<?php

namespace strtob\yii2helpers;

use Yii;
use yii\web\JsExpression;

/**
 * Description of TabularFormHelper
 *
 * @author Tobias Streckel <ts@re-soft.de>
 */
class TabularFormHelper
{

    public static function PersonItem($key, $model, $modelIdField, $entityTagName, $entityTagId)
    {
        $r = '';

        $r .= \kartik\select2\Select2::widget([
                    'name'          => $entityTagName . '[' . $key . '][' . $modelIdField . ']',
                    'value'         => [!empty($model[$modelIdField]) ? $model[$modelIdField] : ''], // default value
                    'data'          => !empty($model[$modelIdField]) ? [$model[$modelIdField] =>
                (
                backend\models\person\Person::findOne($model[$modelIdField])->second_name
                . ', ' . backend\models\person\Person::findOne($model[$modelIdField])->first_name
                )] : ['' => ''],
                    'id'            => $entityTagId . '-' . $key . '-' . $modelIdField,
                    //'initValueText' => isset($model['maning_director_tbl_person_id']) ? app\models\Person::findOne($model['maning_director_tbl_person_id'])->second_name . ', ' . app\models\Person::findOne($model['maning_director_tbl_person_id'])->first_name: '',
                    'options'       => [
                        'placeholder' => Yii::t('app', 'Choose...'),
                    ],
                    'pluginOptions' => [
                        'allowClear'         => true,
                        'minimumInputLength' => 2,
                        'language'           => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax'               => [
                            'url'      => \yii\helpers\Url::to(['/lookup-service/person-company']),
                            'dataType' => 'json',
                            'data'     => new JsExpression('function(params) { return {q:params.term}; }')
                        ],
                        'escapeMarkup'       => new JsExpression('function (markup) { return markup; }'),
                        'templateResult'     => new JsExpression('function(data) { return data.text; }'),
                        'templateSelection'  => new JsExpression('function (data) { return data.text; }'),
                    ],
//                                'pluginEvents'  => [
//                                    'select2:select' => 'function() { setMdData(this); }',
//                                ],
        ]);

        return $r;
    }

    public static function UserItem($key, $model, $modelIdField, $entityTagName, $entityTagId)
    {
        $r = '';

        $data = \yii\helpers\ArrayHelper::map(\backend\models\user\User::find()
                                ->all(), 'id', function ($model) {
                            return $model->fullName;
                        });

        return self::Select2($key, $model, $modelIdField, $entityTagName, $entityTagId, $data);
    }

    
    public static function Select2($key, $model, $modelIdField, $entityTagName, $entityTagId, $data)
    {
        $r = '';

        $r .= \kartik\select2\Select2::widget([
                    'name'          => $entityTagName . '[' . $key . '][' . $modelIdField . ']',
                    'value'         => isset($model[$modelIdField]) ? $model[$modelIdField] : null,
                    'data'          => $data,
                    'options'       => [
                        'placeholder' => 'Choose...',
                        'id'          => $entityTagId . '-' . $key . '-' . $modelIdField,
                    ],
                    'pluginOptions' => [
                        'allowClear' => false,
                    ],
        ]);

        return $r;
    }

    public static function Textarea($key, $model, $modelIdField, $entityTagName, $entityTagId, $row = 6)
    {
        $r = '';

        $r .= \yii\bootstrap5\Html::textarea($entityTagName . '[' . $key . '][' . $modelIdField . ']',
                                             isset($model[$modelIdField]) ? $model[$modelIdField] : null,
                                                   [
                            'rows'  => $row,
                            'id'    => $entityTagId . '-' . $key . '-' . $modelIdField,
                            'class' => 'form-control',
        ]);

        return $r;
    }

    public static function TextInput($key, $model, $modelIdField, $entityTagName, $entityTagId)
    {
        $r = '';

        $r .= \yii\bootstrap5\Html::textInput($entityTagName . '[' . $key . '][' . $modelIdField . ']',
                                              isset($model[$modelIdField]) ? $model[$modelIdField] : null,
                                                    [
                            'id'    => $entityTagId . '-' . $key . '-' . $modelIdField,
                            'class' => 'form-control',
        ]);

        return $r;
    }

    public static function DateControl($key, $model, $modelIdField, $entityTagName, $entityTagId, $displayFormat = 'php:d.m.Y (H:i)')
    {
        $r = '';

        $r .= \kartik\datecontrol\DateControl::widget([
                    'type'          => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
                    'name'          => $entityTagName . '[' . $modelIdField . ']',
                    'value'         => (isset($model[$modelIdField]) ? $model[$modelIdField] : date('Y-m-d H:i:s')), // default value 
                    'options'       => [
                        'id'          => $entityTagId . '-' . $key . '-' . $modelIdField,
                        'placeholder' => '(' . Yii::t('app', 'Choose...') . ')',
                    ],
                    'displayFormat' => $displayFormat,
//                                'saveFormat' => 'php:Y-M-d H:i:s',
//                                'autoclose' => true
        ]);

        return $r;
    }

    public static function CheckboxX($key, $model, $modelIdField, $entityTagName, $entityTagId, $size = 'SM')
    {
        $r = '';

        $r .= \kartik\checkbox\CheckboxX::widget([
                    'name'          => $entityTagName . '[' . $modelIdField . ']',
                    'options'       => [
                        'id'    => $entityTagId . '-' . $key . '-' . $modelIdField,
                        'class' => 'form-control',
                    ],
                    'pluginOptions' => [
                        'threeState' => false,
                        'size'       => $size,
                    ],
                    'pluginEvents'  => [
                        'change' => 'function() { }',
                    ],
        ]);

        return $r;
    }

}
