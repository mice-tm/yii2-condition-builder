<?php

namespace micetm\conditions\actions\conditions;

use kartik\depdrop\DepDropAction;
use micetm\conditions\models\constructor\attributes\MultipleAttribute;
use micetm\conditions\services\ConstructorService;

class ViewValuesAction extends DepDropAction
{
    protected $constructor;

    public function __construct(
        $id,
        $module,
        ConstructorService $constructor,
        array $config = []
    ) {
        $this->constructor = $constructor;
        parent::__construct($id, $module, $config);
    }

    protected function getOutput($attribute, $params = [])
    {
        $model = $this->constructor->getAttribute($attribute);
        if (!$model instanceof MultipleAttribute) {
            return [];
        }
        $values = $model->getData();
        array_walk($values, function (&$value, $i) {
            $value = [
                'id' => $value,
                'name' => $value,
            ];
        });

        return $values;
    }
}