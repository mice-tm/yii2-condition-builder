<?php

namespace micetm\conditions\models\constructor\attributes;

use micetm\conditionsBase\models\AttributeInterface;

class BoolAttribute extends AbstractAttribute implements AttributeInterface
{
    public $comparisons = self::availableComparisons;

    const availableComparisons = [
        '=',
    ];
}