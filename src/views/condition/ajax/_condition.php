<?php

use micetm\conditions\widgets\FieldSet;
use micetm\conditions\models\constructor\conditions\Condition;

/* @var $this yii\web\View */
/* @var $model Condition */
/* @var $availableAttributes array */
/* @var $path string */
/* @var $level integer */
/* @var $position integer */

echo FieldSet::widget([
    'model' => $model,
    'availableAttributes' => $availableAttributes,
    'path' => $path,
    'level' => $level,
    'position' => $position,
    'comparisonUrl' => $comparisonUrl,
    'valueUrl' => $valueUrl
]);
