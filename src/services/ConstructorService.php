<?php

namespace micetm\conditions\services;


use micetm\conditions\models\constructor\attributes\AbstractAttribute;
use micetm\conditions\models\constructor\attributes\activeRecords\Attribute;
use micetm\conditions\models\constructor\attributes\activeRecords\AttributeInterface;
use micetm\conditions\models\constructor\AttributesRepository;
use micetm\conditions\models\constructor\conditions\Condition;
use micetm\conditions\models\constructor\exceptions\AttributeNotFoundException;
use micetm\conditions\models\constructor\search\AttributeSearchInterface;
use yii\helpers\ArrayHelper;

class ConstructorService
{
    /**
     * @var AbstractAttribute[]
     */
    protected $availableAttributes;

    /**
     * @var array
     */
    protected $attributes;

    public function __construct(AttributeSearchInterface $attributeSearch, array $attributes)
    {
        $this->attributes = $attributes;
        $this->availableAttributes = $this->initAvailableAttributesList($attributeSearch);
    }

    /**
     * @return AbstractAttribute[]
     */
    public function getAvailableAttributes()
    {
        return $this->availableAttributes;
    }

    protected function initAttribute(AttributeInterface $attribute)
    {
        $className = $this->attributes[AbstractAttribute::TYPE_DEFAULT];

        if (!empty($this->attributes[$attribute->getType()])) {
            $className = $this->attributes[$attribute->getType()];
        }
        $model = \Yii::$container->get($className);
        $model->load($attribute->toArray(), '');
        return $model;
    }

    public function getAttribute($title)
    {
        if (!isset($this->availableAttributes[$title])) {
            throw new AttributeNotFoundException($title);
        }
        return $this->availableAttributes[$title];
    }

    public function createConditionModels(array $rawData)
    {
        $result = [];

        if (!is_array($rawData['conditions'])) {
            return $result;
        }

        foreach ($rawData['conditions'] as $rawCondition) {
            $condition = new Condition();
            $condition->attributes = $rawCondition;

            if ($condition->attribute) {
                $condition->value = $this->getAttribute($condition->attribute)->value($condition->value);
            }

            $condition->conditionModels = $this->createConditionModels($rawCondition);
            $result[] = $condition;
        }

        return $result;
    }
  
    private function initAvailableAttributesList(AttributeSearchInterface $attributeSearch)
    {
        return ArrayHelper::index(array_map(function (AttributeInterface $attribute) {
            return $this->initAttribute($attribute);
        }, $attributeSearch->search()->all()), 'key');
    }
}