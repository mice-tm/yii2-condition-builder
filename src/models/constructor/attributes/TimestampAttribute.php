<?php
namespace micetm\conditions\models\constructor\attributes;

use micetm\conditionsBase\models\AttributeInterface;

class TimestampAttribute extends AbstractAttribute implements AttributeInterface
{
    /** @var array */
    public $comparisons = self::availableComparisons;

    const availableComparisons = [
        '=',
        '>',
        '>=',
        '<',
        '<='
    ];

    public function value($value)
    {
//        return $value;
        return is_numeric($value) ? $value : strtotime($value);
    }
}
