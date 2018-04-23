<?php

namespace micetm\conditions;

use kartik\select2\Select2Asset;
use yii\bootstrap\BootstrapAsset;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

class ConstructorAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/assets';
    public $js = [
        'js/constructor.js',
        'js/Counter.js'
    ];
    public $css = ['css/constructor.css'];
    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
        Select2Asset::class
    ];
}