<?php

namespace micetm\conditions\actions\conditions;

use kartik\depdrop\DepDropAction;

class ViewComparisonsAction extends DepDropAction
{
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
        $comparisons = $model->comparisons;
        array_walk($comparisons, function (&$comparison, $i) {
            $comparison = [
                'id' => $comparison,
                'name' => $comparison,
            ];
        });

        return $comparisons;
    }
}