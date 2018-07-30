<?php

namespace micetm\conditions\models\constructor\comparisons;

use micetm\conditions\models\constructor\conditions\Condition;

interface ComparisonInterface
{
    public function buildFilter(Condition $condition): array;

    public static function isMaster(Condition $condition): bool;
}