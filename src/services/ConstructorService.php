<?php

namespace micetm\conditions\services;


use micetm\conditions\models\constructor\attributes\AbstractAttribute;
use micetm\conditions\models\constructor\attributes\activeRecords\Attribute;
use micetm\conditions\models\constructor\attributes\activeRecords\AttributeInterface;
use micetm\conditions\models\constructor\AttributesRepository;
use yii\helpers\ArrayHelper;

class ConstructorService
{
    /**
     * @var AttributesRepository
     */
    protected $repository;

    /**
     * @var array
     */
    protected $attributes;

    public function __construct(AttributesRepository $repository, $attributes)
    {
        $this->repository = $repository;
        $this->attributes = $attributes;
    }

    public function getAvailableAttributes()
    {
        /**
         * @var AttributeInterface[]
         */
        $attributes = $this->repository->getAvailableAttributes();
        return ArrayHelper::index(array_map(function (AttributeInterface $attribute) {
            return $this->initAttribute($attribute);
        }, $attributes), 'key');
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
        return $this->initAttribute($this->repository->getAttribute($title));
    }
}