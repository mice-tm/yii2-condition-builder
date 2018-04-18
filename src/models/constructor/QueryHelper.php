<?php

namespace micetm\conditions\models\constructor;

use micetm\conditions\models\constructor\queries\Query;

class QueryHelper
{

    /**
     * @param $conditions
     * @return array
     */
    public static function create($conditions)
    {
        $query = [];
        if ($conditions instanceof \ArrayObject || is_array($conditions)) {
            foreach ($conditions as $condition) {
                $queryModel = new Query($condition);
                $query[] = $queryModel->getQuery();
            }
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