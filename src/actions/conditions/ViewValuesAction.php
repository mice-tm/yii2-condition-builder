<?php

namespace micetm\conditions\actions\conditions;

use kartik\depdrop\DepDropAction;
use micetm\conditions\services\ConstructorService;
use Yii;

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
        $model = $this->constructor->getAttribute($attribute, Yii::$app->getRequest()->post('filterParams'));
        if (!$model->multiple) {
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