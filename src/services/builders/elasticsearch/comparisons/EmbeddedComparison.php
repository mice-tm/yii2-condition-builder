<?php

namespace micetm\conditions\services\builders\elasticsearch\comparisons;

use micetm\conditionsBase\models\AttributeInterface;
use micetm\conditions\models\constructor\conditions\Condition;
use micetm\conditions\models\constructor\queries\Query;
use micetm\conditionsBase\models\ComparisonInterface;

class EmbeddedComparison implements ComparisonInterface
{
    public static function isMaster(Condition $condition): bool
    {
        return AttributeInterface::EMBEDDED_COMPARISON === $condition->comparison;
    }

    public function buildFilter(Condition $condition): array
    {
        if (!is_array($condition->value)) {
            throw new \RuntimeException('Nested comparison should have value of type Array');
        }

        $query['nested']['path'] = $condition->attribute;
        $query['nested']['score_mode'] = 'avg';
        foreach ($condition->value as $key => $value) {
            $query['nested']['query']['bool']['must'][]["match"][$condition->attribute . '.' . $key]
                = $value;
        }

        return $query;
    }
}
