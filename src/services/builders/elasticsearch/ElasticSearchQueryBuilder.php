<?php
namespace micetm\conditions\services\builders\elasticsearch;

use micetm\conditions\models\constructor\comparisons\ComparisonManager;
use micetm\conditionsBase\exceptions\WrongComparison;
use micetm\conditionsBase\models\ConditionInterface;
use micetm\conditionsBase\services\BuilderInterface;

class QueryBuilder extends QueryHelper implements BuilderInterface
{
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

    public function __construct(ComparisonManager $comparisonManager)
    {
        $this->comparisonManager = $comparisonManager;
    }

    /**
     * @param $conditions
     * @return array
     * @throws WrongComparison
     */
    public function create($conditions):array
    {
        $query = [];

        if ($conditions instanceof \ArrayObject || is_array($conditions)) {
            foreach ($conditions as $condition) {
                $query[] = $this->getQuery($condition);
            }
        }

        if (empty($query)) {
            return [];
        }
        if (1 == count($query)) {
            return [
                "query" => array_shift($query)
            ];
        }

        return [
            "query" => [
                "bool" => [
                    Query::OPERATOR_OR => $query
                ]
            ]
        ];
    }

    /**
     * @return array|null
     * @throws WrongComparison
     */
    protected function getQuery(ConditionInterface $condition)
    {
        if (count($condition->conditionModels)) {
            $query = [];
            foreach ($condition->conditionModels as $condition) {
                $queryModel = new self($this->comparisonManager, $condition);
                if (!empty($filter = $queryModel->getQuery())) {
                    $query["bool"][self::CONDITIONS[$condition->operator]][] = $filter;
                }
            }
            return $query;
        } elseif ($condition->attribute) {
            $comparison = $this->comparisonManager->getComparison($condition);
            return $comparison->buildFilter($condition);
        }
        return;
    }
}
