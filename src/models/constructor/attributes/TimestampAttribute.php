<?php
namespace micetm\conditions\models\constructor\attributes;

class TimestampAttribute extends AbstractAttribute
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
        return strtotime($value);
    }
}
