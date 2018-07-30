<?php

namespace micetm\conditions\models\constructor\comparisons;

use micetm\conditions\models\constructor\attributes\AbstractAttribute;
use micetm\conditions\models\constructor\conditions\Condition;
use micetm\conditions\models\constructor\queries\Query;

class LikeComparison implements ComparisonInterface
{
    public static function isMaster(Condition $condition): bool
    {
        return AbstractAttribute::LIKE_COMPARISON === $condition->comparison;
    }

    public function buildFilter(Condition $condition): array
    {
        $query["match"][$condition->attribute] = $condition->value;
        return $query;
    }
}
