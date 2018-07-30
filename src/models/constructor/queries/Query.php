<?php

namespace micetm\conditions\models\constructor\queries;


use micetm\conditions\models\constructor\attributes\AbstractAttribute;
use micetm\conditions\models\constructor\comparisons\ComparisonManager;
use micetm\conditions\models\constructor\conditions\Condition;

class Query
{
    /** @var Condition */
    public $condition;

    /** @var ComparisonManager */
    public $comparisonManager;

    const OPERATOR_AND = "must";
    const OPERATOR_OR = "should";
    const OPERATOR_NOT = "must_not";

    const RANGE_PARAMETER_GREATER_THAN_OR_EQUAL_TO = 'gte';
    const RANGE_PARAMETER_GREATER_THAN = 'gt';
    const RANGE_PARAMETER_LESS_THAN_OR_EQUAL_TO = 'lte';
    const RANGE_PARAMETER_LESS_THAN = 'lt';

    const CONDITIONS = [
        Condition::OPERATOR_AND => self::OPERATOR_AND,
        Condition::OPERATOR_OR => self::OPERATOR_OR,
        Condition::OPERATOR_NOT => self::OPERATOR_NOT,
        Condition::OPERATOR_STATEMENT => self::OPERATOR_OR,
    ];

    public function __construct(ComparisonManager $comparisonManager, Condition $condition)
    {
        $this->condition = $condition;
        $this->comparisonManager = $comparisonManager;
    }

    /**
     * @return array
     */
    public function getQuery()
    {
        if (count($this->condition->conditionModels)) {
            foreach ($this->condition->conditionModels as $condition) {
                $queryModel = new self($this->comparisonManager, $condition);
                if (!empty($filter = $queryModel->getQuery())) {
                    $query["bool"][self::CONDITIONS[$this->condition->operator]][] = $filter;
                }
            }
            return $query;
        } elseif ($this->condition->attribute) {
            $comparison = $this->comparisonManager->getComparison($this->condition);
            if (empty($comparison)) {
                return;
            }
            return $comparison->buildFilter($this->condition);
        }
        return;
    }
}
