<?php

namespace micetm\conditions\models\constructor\queries;


use micetm\conditions\models\constructor\attributes\AbstractAttribute;
use micetm\conditions\models\constructor\conditions\Condition;

class Query
{
    public $condition;

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

    public function __construct(Condition $condition)
    {
        $this->condition = $condition;
    }

    /**
     * @return array
     */
    public function getQuery()
    {
        $query = [];
        if (count($this->condition->ConditionModels)) {
            foreach ($this->condition->ConditionModels as $condition) {
                $queryModel = new self($condition);
                $query["bool"][self::CONDITIONS[$this->condition->operator]][] = $queryModel->getQuery();
            }
            return $query;
        } elseif ($this->condition->attribute) {
            if (AbstractAttribute::EQUAL_TO_COMPARISON === $this->condition->comparison) {
                if (is_array($this->condition->value)) {
                    $query["terms"][$this->condition->attribute] = $this->condition->value;
                    return $query;
                }
                $query["term"][$this->condition->attribute] = $this->condition->value;
                return $query;
            }
            if (AbstractAttribute::MORE_THAN_ONE_IN_COMPARISON === $this->condition->comparison) {
                $query["terms"][$this->condition->attribute] =
                    is_array($this->condition->value)
                        ? $this->condition->value
                        : [$this->condition->value]
                ;
                return $query;
            }
            if (AbstractAttribute::GREATER_THAN_COMPARISON === $this->condition->comparison) {
                $query["range"][$this->condition->attribute][self::RANGE_PARAMETER_GREATER_THAN]
                    = $this->condition->value;
                return $query;
            }
            if (AbstractAttribute::LESS_THAN_COMPARISON === $this->condition->comparison) {
                $query["range"][$this->condition->attribute][self::RANGE_PARAMETER_LESS_THAN]
                    = $this->condition->value;
                return $query;
            }
            if (AbstractAttribute::GREATER_THAN_OR_EQUAL_TO_COMPARISON === $this->condition->comparison) {
                $query["range"][$this->condition->attribute][self::RANGE_PARAMETER_GREATER_THAN_OR_EQUAL_TO]
                    = $this->condition->value;
                return $query;
            }
            if (AbstractAttribute::LESS_THAN_OR_EQUAL_TO_COMPARISON === $this->condition->comparison) {
                $query["range"][$this->condition->attribute][self::RANGE_PARAMETER_LESS_THAN_OR_EQUAL_TO]
                    = $this->condition->value;
                return $query;
            }
            if (AbstractAttribute::LIKE_COMPARISON === $this->condition->comparison) {
                $query["match"][$this->condition->attribute] = $this->condition->value;
                return $query;
            }
        }
    }
}