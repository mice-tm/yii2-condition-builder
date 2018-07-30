<?php

namespace micetm\conditions\models\constructor\comparisons;

use micetm\conditions\models\constructor\attributes\AbstractAttribute;
use micetm\conditions\models\constructor\conditions\Condition;
use micetm\conditions\models\constructor\queries\Query;

class DefaultComparison implements ComparisonInterface
{
    public static function isMaster(Condition $condition): bool
    {
        return true;
    }

    public function buildFilter(Condition $condition): array
    {
        if (AbstractAttribute::EQUAL_TO_COMPARISON === $condition->comparison) {
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
        if (AbstractAttribute::MORE_THAN_ONE_IN_COMPARISON === $condition->comparison) {
            $query["bool"][Query::OPERATOR_OR][]["terms"][$condition->attribute . ".raw"]
                = is_array($condition->value) ? $condition->value : [$condition->value];
            $query["bool"][Query::OPERATOR_OR][]["terms"][$condition->attribute]
                = is_array($condition->value) ? $condition->value : [$condition->value];
            ;
            return $query;
        }
        if (AbstractAttribute::GREATER_THAN_COMPARISON === $condition->comparison) {
            $query["range"][$condition->attribute][Query::RANGE_PARAMETER_GREATER_THAN]
                = $condition->value;
            return $query;
        }
        if (AbstractAttribute::LESS_THAN_COMPARISON === $condition->comparison) {
            $query["range"][$condition->attribute][Query::RANGE_PARAMETER_LESS_THAN]
                = $condition->value;
            return $query;
        }
        if (AbstractAttribute::GREATER_THAN_OR_EQUAL_TO_COMPARISON === $condition->comparison) {
            $query["range"][$condition->attribute][Query::RANGE_PARAMETER_GREATER_THAN_OR_EQUAL_TO]
                = $condition->value;
            return $query;
        }
        if (AbstractAttribute::LESS_THAN_OR_EQUAL_TO_COMPARISON === $condition->comparison) {
            $query["range"][$condition->attribute][Query::RANGE_PARAMETER_LESS_THAN_OR_EQUAL_TO]
                = $condition->value;
            return $query;
        }
        if (AbstractAttribute::LIKE_COMPARISON === $condition->comparison) {
            $query["match"][$condition->attribute] = $condition->value;
            return $query;
        }
    }
}
