<?php

namespace micetm\conditions\services\builders\elasticsearch\comparisons;

use micetm\conditions\models\constructor\attributes\AbstractAttribute;
use micetm\conditions\models\constructor\conditions\Condition;
use micetm\conditions\models\constructor\queries\Query;
use micetm\conditionsBase\models\ComparisonInterface;

class DefaultComparison implements ComparisonInterface
{
    public static function isMaster(Condition $condition): bool
    {
        return AbstractAttribute::EQUAL_TO_COMPARISON === $condition->comparison;
    }

    public function buildFilter(Condition $condition): array
    {
        if (is_array($condition->value)) {
            $query["bool"][Query::OPERATOR_OR][]["terms"][$condition->attribute . ".raw"]
                = $condition->value;
            $query["bool"][Query::OPERATOR_OR][]["terms"][$condition->attribute]
                = $condition->value;
            return $query;
        }
        $query["bool"][Query::OPERATOR_OR][]["term"][$condition->attribute . ".raw"]
            = $condition->value;
        $query["bool"][Query::OPERATOR_OR][]["match_phrase"][$condition->attribute]
            = $condition->value;
        return $query;
    }
}
