<?php

namespace micetm\conditions\models\constructor;

use micetm\conditions\models\constructor\comparisons\ComparisonManager;
use micetm\conditions\models\constructor\queries\Query;

class QueryHelper
{

    /**
     * @param $conditions
     * @return array
     * @throws \micetm\conditions\exceptions\WrongComparison
     */
    public static function create($conditions)
    {
        $query = [];

        if ($conditions instanceof \ArrayObject || is_array($conditions)) {
            foreach ($conditions as $condition) {
                $queryModel = new Query(new ComparisonManager(), $condition);
                $query[] = $queryModel->getQuery();
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