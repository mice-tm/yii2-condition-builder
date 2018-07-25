<?php

namespace micetm\conditions\actions\conditions;

use kartik\depdrop\DepDropAction;
use micetm\conditions\services\ConstructorService;

class ViewValuesAction extends DepDropAction
{
    /**
     * @var ConstructorService
     */
    public $constructor;

    public function __construct(
        $id,
        $module,
        array $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    protected function getOutput($attribute, $params = [])
    {
        $model = $this->constructor->getAttribute($attribute);
        $values = $model->getData();
        if (!$values) {
            return [];
        }

        array_walk($values, function (&$value, $i) {
            $value = [
                'id' => $value,
                'name' => $value,
            ];
        });

        return $values;
    }
}