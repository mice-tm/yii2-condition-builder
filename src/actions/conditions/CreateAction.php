<?php

namespace micetm\conditions\actions\conditions;

use micetm\conditionsBase\models\AttributeInterface;
use micetm\conditionsBase\models\ConditionInterface;
use micetm\conditionsBase\models\constructor\conditions\Condition;
use micetm\conditionsBase\services\ConstructorService;
use Yii;
use yii\base\Action;

class CreateAction extends Action
{
    /**
     * @var ConstructorService
     */
    public $constructor;
    public $defaultAttribute;
    public $defaultValue;
    public $defaultComparison = AttributeInterface::EQUAL_TO_COMPARISON;
    public $comparisonUrl;
    public $valueUrl;
    public $constructorView = '@micetm/conditions/views/condition/ajax/_condition';

    /**
     * CreateAction constructor.
     * @param $id
     * @param $module
     * @param array $config
     */
    public function __construct(
      $id,
      $module,
      array $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    /**
     * @return string
     */
    public function run()
    {
        $availableAttributes = $this->constructor->getAvailableAttributes();
        $request = Yii::$app->getRequest();
        $level = $request->getBodyParam('level', null);
        $position = $request->getBodyParam('position', 0);
        $path = $request->getBodyParam('path', '');

        return $this->controller->renderAjax(
          $this->constructorView,
          [
            'model' => new Condition(
              [
                'operator' => ConditionInterface::OPERATOR_STATEMENT,
                'attribute' => $this->defaultAttribute,
                'comparison' => $this->defaultComparison,
                "value" => $this->defaultValue,
              ]
            ),
            'availableAttributes' => $availableAttributes,
            'path' => $path,
            'level' => is_null($level) ? 0 : $level + 1,
            'position' => $position,
            'comparisonUrl' => $this->comparisonUrl,
            'valueUrl' => $this->valueUrl
          ]
        );
    }
}
