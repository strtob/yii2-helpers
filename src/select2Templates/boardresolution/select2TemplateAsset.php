<?php

namespace backend\components\helpers\select2Templates\boardresolution;

use yii\web\AssetBundle;

/**
 * Theme main asset bundle.
 */
class select2TemplateAsset extends AssetBundle
{

    /**
     * @inheritdoc
     */
    public $sourcePath = '@backend/components/helpers/select2Templates/boardresolution';

    /**
     * @inheritdoc
     */
//    public $css = [
//        'resultParser.js',
//    ];
    public $js = [
        'format.js',
        'resultParser.js',
    ];
    
    /**
     * @inheritdoc
     */
    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD,
    ];

    /**
     * @inheritdoc
     */
    public $depends = [       
        'yii\web\JqueryAsset',
        'yii\bootstrap5\BootstrapAsset',
        'yii\bootstrap5\BootstrapPluginAsset',
    ];
}
