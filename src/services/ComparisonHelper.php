<?php

namespace micetm\conditions\services;

use micetm\conditions\models\constructor\attributes\AbstractAttribute;
use micetm\conditions\models\constructor\conditions\Condition;

class ComparisonHelper
{
    const COMPARISONS_ASSERTS = [
        AbstractAttribute::EQUAL_TO_COMPARISON => 'eq',
        AbstractAttribute::GREATER_THAN_COMPARISON => 'gt',
        AbstractAttribute::GREATER_THAN_OR_EQUAL_TO_COMPARISON => 'gte',
        AbstractAttribute::LESS_THAN_COMPARISON => 'lt',
        AbstractAttribute::LESS_THAN_OR_EQUAL_TO_COMPARISON => 'lte',
        AbstractAttribute::LIKE_COMPARISON => 'like',
        AbstractAttribute::MORE_THAN_ONE_IN_COMPARISON => 'in',
    ];

    /**
     * @param Condition $condition
     * @param $value
     * @return bool
     */
    public static function compare(Condition $condition, $value)
    {
        if (empty(self::COMPARISONS_ASSERTS[$condition->comparison])) {
            return self::in($condition->value, $value);
        }

        return self::{self::COMPARISONS_ASSERTS[$condition->comparison]}($condition->value, $value);
    }

    /**
     * @param mixed $a
     * @param mixed $b
     * @return bool
     */
    public static function in($a, $b)
    {
        return !empty(array_intersect((array)$a, (array)$b));
    }

    /**
     * @param mixed $a
     * @param mixed $b
     * @return bool
     */
    public static function eq($a, $b)
    {
        return $a == $b;
    }

    /**
     * @param mixed $a
     * @param mixed $b
     * @return bool
     */
    public static function gt($a, $b)
    {
        return $a > $b;
    }

    /**
     * @param mixed $a
     * @param mixed $b
     * @return bool
     */
    public static function gte($a, $b)
    {
        return $a >= $b;
    }

    /**
     * @param mixed $a
     * @param mixed $b
     * @return bool
     */
    public static function lt($a, $b)
    {
        return $a < $b;
    }

    /**
     * @param mixed $a
     * @param mixed $b
     * @return bool
     */
    public static function lte($a, $b)
    {
        return $a <= $b;
    }

    /**
     * @param mixed $a
     * @param mixed $b
     * @return bool
     */
    public static function like($a, $b)
    {
        return false !== strpos($b, $a);
    }
}
