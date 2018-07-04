<?php

namespace micetm\conditions\models\constructor\attributes;

class BoolAttribute extends AbstractAttribute
{
    public $comparisons = self::availableComparisons;

    const availableComparisons = [
        '=',
    ];
}