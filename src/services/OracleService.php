<?php

namespace micetm\conditions\services;

use micetm\conditions\models\constructor\conditions\Condition;
use yii\helpers\ArrayHelper;

class OracleService implements OracleInterface
{
    const OPERATORS_ASSERTS = [
        Condition::OPERATOR_AND => 'conditionAND',
        Condition::OPERATOR_OR => 'conditionOR',
        Condition::OPERATOR_NOT => 'conditionNOT',
    ];

    /**
     * Returns true if $target satisfies $condition
     *
     * @param Condition $condition
     * @param array|object $target
     * @return bool
     */
    public function speak(Condition $condition, $target): bool
    {
        if ($condition->isUnary()) {
            $value = (array) ArrayHelper::getValue($target, $condition->attribute);
            if (empty($value)) {
                return false;
            }
            return ComparisonHelper::compare($condition, $value);
        }

        return $this->{self::OPERATORS_ASSERTS[$condition->operator]}($condition->conditionModels, $target);
    }

    /**
     * @param $conditions
     * @param $target
     * @return bool
     */
    public function conditionOR($conditions, $target)
    {
        foreach ($conditions as $condition) {
            if ($this->speak($condition, $target)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $conditions
     * @param $target
     * @return bool
     */
    public function conditionAND($conditions, $target)
    {
        foreach ($conditions as $condition) {
            if (!$this->speak($condition, $target)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param $conditions
     * @param $target
     * @return bool
     */
    public function conditionNOT($conditions, $target)
    {
        return ! $this->speak(current($conditions), $target);
    }
}
