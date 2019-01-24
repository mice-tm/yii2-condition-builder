<?php
namespace test\unit;

use micetm\conditions\models\constructor\attributes\AbstractAttribute;
use micetm\conditions\models\constructor\conditions\Condition;
use micetm\conditions\services\QueryBuilder;
use PHPUnit\Framework\TestCase;

class MyTest extends TestCase
{
    /**
     * @dataProvider providerUnarySuccess
     */
    public function testUnarySuccess(Condition $condition, $expectedQuery)
    {
        $queryBuilder = new QueryBuilder();
        $query = $queryBuilder->create([$condition]);
        $this->assertEquals($query, $expectedQuery);
    }

    public function providerUnarySuccess()
    {
        return [
            [
                'condition' => new Condition([
                    'attribute' => 'items.id',
                    'comparison' => AbstractAttribute::EQUAL_TO_COMPARISON,
                    'value' => 55555
                ]),
                'expectedQuery' => [
                    "query" => [
                        "bool" => [
                            "should" => [
                                ["term" => ["items.id.raw" => 55555]],
                                ["match_phrase" => ["items.id" => 55555]]
                            ]
                        ]
                    ]
                ],
            ]
        ];
    }

}