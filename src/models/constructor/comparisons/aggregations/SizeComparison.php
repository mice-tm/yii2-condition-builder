<?php

namespace micetm\conditions\models\constructor\comparisons\aggregations;

use micetm\conditions\models\constructor\comparisons\ComparisonInterface;
use micetm\conditions\models\constructor\conditions\Condition;

class SizeComparison implements ComparisonInterface
{
    const AGGREGATIONS_PREFIX = 'aggregation';
    const OPERATION_SUFFIX = 'count';

    public function buildFilter(Condition $condition): array
    {
        self::getPatternMatches($condition->attribute, $matches);
        $query['bool']["filter"]['script']['script'] = sprintf(
            "doc['%s'].values.size() %s %d",
            $matches[1],
            $condition->comparison,
            $condition->value
        );
        return $query;
    }

    public static function isMaster(Condition $condition): bool
    {
        return (bool) self::getPatternMatches($condition->attribute, $matches);
    }

    protected static function getPatternMatches($subject, &$matches)
    {
        return preg_match(
            "/^" . self::AGGREGATIONS_PREFIX . "\.(.+)\." . self::OPERATION_SUFFIX . "$/",
            $subject,
            $matches
        );
    }
}
