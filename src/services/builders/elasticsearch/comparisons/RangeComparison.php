<?php

namespace micetm\conditions\services\builders\elasticsearch\comparisons;

use micetm\conditions\models\constructor\conditions\Condition;
use micetm\conditions\models\constructor\queries\Query;
use micetm\conditionsBase\models\AttributeInterface;
use micetm\conditionsBase\models\ComparisonInterface;

class RangeComparison implements ComparisonInterface
{
    public static function isMaster(Condition $condition): bool
    {
        return in_array(
            $condition->comparison,
            [
                AttributeInterface::GREATER_THAN_COMPARISON,
                AttributeInterface::LESS_THAN_COMPARISON,
                AttributeInterface::GREATER_THAN_OR_EQUAL_TO_COMPARISON,
                AttributeInterface::LESS_THAN_OR_EQUAL_TO_COMPARISON
            ]
        );
    }

    public function buildFilter(Condition $condition): array
    {
        if (AttributeInterface::GREATER_THAN_COMPARISON === $condition->comparison) {
            $query["range"][$condition->attribute][Query::RANGE_PARAMETER_GREATER_THAN]
                = $condition->value;
            return $query;
        }
        if (AttributeInterface::LESS_THAN_COMPARISON === $condition->comparison) {
            $query["range"][$condition->attribute][Query::RANGE_PARAMETER_LESS_THAN]
                = $condition->value;
            return $query;
        }
        if (AttributeInterface::GREATER_THAN_OR_EQUAL_TO_COMPARISON === $condition->comparison) {
            $query["range"][$condition->attribute][Query::RANGE_PARAMETER_GREATER_THAN_OR_EQUAL_TO]
                = $condition->value;
            return $query;
        }
        if (AttributeInterface::LESS_THAN_OR_EQUAL_TO_COMPARISON === $condition->comparison) {
            $query["range"][$condition->attribute][Query::RANGE_PARAMETER_LESS_THAN_OR_EQUAL_TO]
                = $condition->value;
            return $query;
        }

        return [];
    }
}
