<?php

namespace micetm\conditions\models\constructor\attributes;


class MultipleAttribute extends AbstractAttribute
{
    /** @var array */
    public $data = [];

    public $multiple = false;

    /** @var array */
    public $comparisons = self::availableComparisons;

    const availableComparisons = [
        'in',
        '=',
    ];

    public function getData(){
        return $this->data;
    }
}