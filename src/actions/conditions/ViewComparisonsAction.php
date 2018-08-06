<?php

namespace micetm\conditions\actions\conditions;

use kartik\depdrop\DepDropAction;
use micetm\conditions\services\ConstructorService;
use Yii;

class ViewComparisonsAction extends DepDropAction
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
        $model = $this->constructor->getAttribute($attribute, Yii::$app->getRequest()->post('filterParams'));
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