<?php

namespace micetm\conditions\services;

use micetm\conditions\models\constructor\attributes\AbstractAttribute;
use micetm\conditions\models\constructor\attributes\activeRecords\Attribute;
use micetm\conditions\models\constructor\attributes\activeRecords\AttributeInterface;
use micetm\conditions\models\constructor\conditions\Condition;
use micetm\conditions\models\constructor\exceptions\AttributeNotFoundException;
use micetm\conditions\models\constructor\search\AttributeSearchInterface;
use templatemonster\admin\modules\discounts\models\search\AttributesSearch;
use yii\base\Component;
use yii\helpers\ArrayHelper;

class ConstructorService extends Component
{
    /**
     * @var AbstractAttribute[]
     */
    protected $availableAttributes;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * @var AttributesSearch
     */
    protected $attributeSearch;

    /**
     * @return AbstractAttribute[]
     */
    public function getAvailableAttributes($params = [], \ArrayObject $conditions = null)
    {
        $availableAttributes = $this->initAvailableAttributesList($params);
        if ($conditions) {
            $this->initCustomAttributes($conditions, $availableAttributes);
        }
        return $availableAttributes;
    }

    /**
     * Retrives custom attributes from Conditions
     * @param \ArrayObject|null $conditions
     */
    protected function initCustomAttributes(\ArrayObject $conditions = null, &$availableAttributes)
    {
        foreach (iterator_to_array($conditions->getIterator()) as $condition) {
            /** @var Condition $condition */
            if (!$condition->isUnary()) {
                $this->initCustomAttributes($condition->conditionModels, $availableAttributes);
                continue;
            }
            if (empty($this->availableAttributes[$condition->attribute])) {
                $availableAttributes[$condition->attribute] = $this->initAttribute(
                    new Attribute([
                        'title' => $condition->attribute,
                        'level' => 'not defined',
                        'type' => 'default',
                        'key' => $condition->attribute,
                        'status' => AbstractAttribute::STATUS_INACTIVE,
                        'multiple' => is_array($condition->value),
                    ])
                );
            }
        }
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

    public function getAttribute($title, $extraFilters = [])
    {
        $availableAttributes = $this->initAvailableAttributesList($extraFilters);
        if (!isset($availableAttributes[$title])) {
            throw new AttributeNotFoundException($title);
        }
        return $availableAttributes[$title];
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
                $condition->value = $this->getAttribute($condition->attribute)
                    ->value($condition->value);
            }

            $condition->conditionModels = $this->createConditionModels($rawCondition);
            $result[] = $condition;
        }

        return $result;
    }

    protected function initAvailableAttributesList($params = [])
    {
        $this->attributeSearch->load($params, '');
        return ArrayHelper::index(array_map(function (AttributeInterface $attribute) {
            return $this->initAttribute($attribute);
        }, $this->attributeSearch->search()->all()), 'key');
    }

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }

    /**
     * @param AttributesSearch $attributeSearch
     */
    public function setAttributeSearch(AttributesSearch $attributeSearch): void
    {
        $this->attributeSearch = $attributeSearch;
    }
}