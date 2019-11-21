<?php
namespace micetm\conditions\models\constructor\attributes;

use micetm\conditionsBase\models\AttributeInterface;

class TimestampAttribute extends AbstractAttribute implements AttributeInterface
{
    /** @var array */
    public $comparisons = self::availableComparisons;

    const availableComparisons =  [
        self::EQUAL_TO_COMPARISON => self::EQUAL_TO_COMPARISON,
        self::GREATER_THAN_COMPARISON => self::GREATER_THAN_COMPARISON,
        self::GREATER_THAN_OR_EQUAL_TO_COMPARISON => self::GREATER_THAN_OR_EQUAL_TO_COMPARISON,
        self::LESS_THAN_COMPARISON => self::LESS_THAN_COMPARISON,
        self::LESS_THAN_OR_EQUAL_TO_COMPARISON => self::LESS_THAN_OR_EQUAL_TO_COMPARISON,
    ];

    public function value($value)
    {
//        return $value;
        return is_numeric($value) ? $value : strtotime($value);
    }
}
