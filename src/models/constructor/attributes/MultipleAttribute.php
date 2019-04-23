<?php

namespace micetm\conditions\models\constructor\attributes;

use micetm\conditionsBase\models\AttributeInterface;

/**
 * Class MultipleAttribute
 * @package micetm\conditions\models\constructor\attributes
 * @deprecated any attribute can be multiple and have data
 */
class MultipleAttribute extends AbstractAttribute implements AttributeInterface
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