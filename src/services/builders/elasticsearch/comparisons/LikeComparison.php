<?php

namespace micetm\conditions\services\builders\elasticsearch\comparisons;

use micetm\conditions\models\constructor\conditions\Condition;
use micetm\conditions\models\constructor\queries\Query;
use micetm\conditionsBase\models\AttributeInterface;
use micetm\conditionsBase\models\ComparisonInterface;

class LikeComparison implements ComparisonInterface
{
    public static function isMaster(Condition $condition): bool
    {
        return AttributeInterface::LIKE_COMPARISON === $condition->comparison;
    }

    public function buildFilter(Condition $condition): array
    {
        $query["bool"][Query::OPERATOR_OR][]["match"][$condition->attribute] = $condition->value;
        $query["bool"][Query::OPERATOR_OR][]["wildcard"][$condition->attribute . '.raw'] =
            '*' . strtolower($condition->value) . '*';
        return $query;
    }
}
