<?php

namespace micetm\conditions\actions\conditions;

use micetm\conditionsBase\services\ConstructorService;
use micetm\conditionsBase\models\AttributeInterface;
use micetm\conditionsBase\models\ConditionInterface;
use micetm\conditionsBase\models\constructor\conditions\Condition;
use yii\base\Action;
use Yii;

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

    public function __construct(
        $id,
        $module,
        array $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function init()
    {
        parent::init();

        if (!Yii::getAlias('@constructor-views', false)) {
            Yii::setAlias('@constructor-views', __DIR__ . '/../../views');
        }
    }

    public function run()
    {
        $availableAttributes = $this->constructor->getAvailableAttributes();

        $request = Yii::$app->getRequest();
        $level = $request->getBodyParam('level', null);
        $position = $request->getBodyParam('position', 0);
        $path = $request->getBodyParam('path', '');

        return $this->controller->renderAjax('@constructor-views/condition/ajax/_condition', [
            'model' => new Condition([
                'operator' => ConditionInterface::OPERATOR_STATEMENT,
                'attribute' => $this->defaultAttribute,
                'comparison' => $this->defaultComparison,
                "value" => $this->defaultValue,
            ]),
            'availableAttributes' => $availableAttributes,
            'path' => $path,
            'level' => is_null($level) ? 0 : $level+1,
            'position' => $position,
            'comparisonUrl' => $this->comparisonUrl,
            'valueUrl' => $this->valueUrl
        ]);
    }
}
