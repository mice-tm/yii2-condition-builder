<?php

namespace micetm\conditions\services\builders\elasticsearch\comparisons;

use micetm\conditionsBase\models\AttributeInterface;
use micetm\conditions\models\constructor\conditions\Condition;
use micetm\conditions\models\constructor\queries\Query;
use micetm\conditionsBase\models\ComparisonInterface;

class InComparison implements ComparisonInterface
{
    public static function isMaster(Condition $condition): bool
    {
        return AttributeInterface::MORE_THAN_ONE_IN_COMPARISON === $condition->comparison;
    }

    public function buildFilter(Condition $condition): array
    {
        $query["bool"][Query::OPERATOR_OR][]["terms"][$condition->attribute . ".raw"]
            = is_array($condition->value) ? $condition->value : [$condition->value];
        $query["bool"][Query::OPERATOR_OR][]["terms"][$condition->attribute]
            = is_array($condition->value) ? $condition->value : [$condition->value];
        ;
        return $query;
    }
}
