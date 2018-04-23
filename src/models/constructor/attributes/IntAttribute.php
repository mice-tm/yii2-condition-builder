<?php

namespace micetm\conditions\models\constructor\attributes;

class IntAttribute extends AbstractAttribute
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
}