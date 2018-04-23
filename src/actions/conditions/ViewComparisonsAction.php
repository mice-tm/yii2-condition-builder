<?php

namespace micetm\conditions\actions\conditions;

use kartik\depdrop\DepDropAction;
use micetm\conditions\models\constructor\attributes\AbstractAttribute;
use micetm\conditions\models\constructor\attributes\MultipleAttribute;
use micetm\conditions\models\constructor\conditions\Condition;
use micetm\conditions\services\ConstructorService;
use yii\base\Action;
use Yii;

class ViewComparisonsAction extends DepDropAction
{
    protected $constructor;

    public function __construct(
        $id,
        $module,
        ConstructorService $constructor,
        array $config = []
    )
    {
        $this->constructor = $constructor;
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