<?php

namespace micetm\conditions\models\constructor;

use micetm\conditions\models\constructor\attributes\AbstractAttribute;
use micetm\conditions\models\constructor\attributes\activeRecords\Attribute;
use yii\base\BaseObject;
use yii\mongodb\ActiveQuery;

class AttributesRepository extends BaseObject
{

    /**
     * @var ActiveQuery
     */
    public $attributesQuery;

    public function __construct($attributesQuery, $config = [])
    {
        $this->attributesQuery = $attributesQuery;
        parent::__construct($config);
    }

    /**
     * @return Attribute[]
     */
    public function getAvailableAttributes()
    {
        return $this->attributesQuery
            ->where(['status' => AbstractAttribute::STATUS_ACTIVE ])
            ->orderBy(['key' => SORT_ASC])
            ->all();
    }

    /**
     * @param $key
     * @return Attribute
     */
    public function getAttribute($key)
    {
        return $this->attributesQuery
            ->where(['key' => $key ])
            ->one();
    }
}
