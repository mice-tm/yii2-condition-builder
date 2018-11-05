<?php

namespace micetm\conditions\services;

use micetm\conditions\models\constructor\conditions\Condition;

class FlatConditionsService
{
    /**
     * @param array|\ArrayObject $conditionsList
     * @return Condition[]
     */
    public function create($conditionsList): array
    {
        $result = [];

        /** @var Condition $condition */
        foreach ($conditionsList as $condition) {
            if (count($condition->conditionModels)) {
                $result = array_merge($result, $this->create($condition->conditionModels));
            }

            $result[] = $this->makeFlatClone($condition);
        }

        return $result;
    }

    private function makeFlatClone(Condition $condition)
    {
        $flatCondition = new Condition();
        $flatCondition->load([
            'operator' => $condition->operator,
            'attribute' => $condition->attribute,
            'value' => $condition->value,
            'comparison' => $condition->comparison,
        ], '');

        return $flatCondition;
    }
}
