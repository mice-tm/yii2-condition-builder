<?php

namespace micetm\conditions\models\constructor\comparisons;

use micetm\conditions\models\constructor\attributes\AbstractAttribute;
use micetm\conditions\models\constructor\conditions\Condition;
use micetm\conditions\models\constructor\queries\Query;

class EmbeddedComparison implements ComparisonInterface
{
    public static function isMaster(Condition $condition): bool
    {
        return AbstractAttribute::EMBEDDED_COMPARISON === $condition->comparison;
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
