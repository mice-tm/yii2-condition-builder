<?php
namespace micetm\conditions\models\constructor\search;

use yii\db\ActiveQueryInterface;

interface AttributeSearchInterface
{
    public function search(): ActiveQueryInterface;

    public function load($data, $formName = null);
}
