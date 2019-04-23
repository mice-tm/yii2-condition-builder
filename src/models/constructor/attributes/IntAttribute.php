<?php

namespace micetm\conditions\models\constructor\attributes;

use micetm\conditionsBase\models\AttributeInterface;

class IntAttribute extends AbstractAttribute implements AttributeInterface
{

    /** @var array */
    public $comparisons = self::availableComparisons;

    const availableComparisons = [
        '=',
        '>',
        '>=',
        '<',
        '<=',
        'in',
    ];
}