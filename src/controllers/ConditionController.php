<?php

namespace micetm\conditions\controllers;

use kartik\depdrop\DepDropAction;
use micetm\conditions\models\constructor\attributes\AbstractAttribute;
use micetm\conditions\models\constructor\attributes\MultipleAttribute;
use micetm\conditions\models\constructor\conditions\Condition;
use micetm\conditions\services\ConstructorService;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class ConditionController extends Controller
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

    public function init()
    {
        parent::init();

        if (!Yii::getAlias('@constructor-views')) {
            Yii::setAlias('@constructor-views', dir(__DIR__) . '/views');
        }
    }

    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
            'condition-comparison' => [
                'class' => DepDropAction::class,
                'outputCallback' => function ($attribute) {
                    $model = $this->constructor->getAttribute($attribute);
                    $comparisons =$model->comparisons;
                    array_walk($comparisons, function (&$comparison, $i) {
                        $comparison = [
                            'id' => $comparison,
                            'name' => $comparison,
                        ];
                    });

                    return $comparisons;
                }
            ],
            'condition-value' => [
                'class' => DepDropAction::class,
                'outputCallback' => function ($attribute) {
                    $model = $this->constructor->getAttribute($attribute);
                    if (!$model instanceof MultipleAttribute) {
                        return [];
                    }
                    $values =$model->getData();
                    array_walk($values, function (&$value, $i) {
                        $value = [
                            'id' => $value,
                            'name' => $value,
                        ];
                    });

                    return $values;
                }
            ],
        ]);
    }
}
