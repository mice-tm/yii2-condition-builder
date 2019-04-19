<?php

namespace micetm\conditions\models\constructor;

use micetm\conditions\models\constructor\comparisons\ComparisonManager;
use micetm\conditions\models\constructor\queries\Query;
use micetm\conditionsBase\exceptions\WrongComparison;


class QueryHelper
{


    /**
     * @param $conditions
     * @return array
     * @throws WrongComparison
     */
    public static function create($conditions)
    {
        $query = [];

        if ($conditions instanceof \ArrayObject || is_array($conditions)) {
            foreach ($conditions as $condition) {
                $queryModel = new Query(new ComparisonManager(), $condition);
                $query[] = $this->getQuery();
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
}