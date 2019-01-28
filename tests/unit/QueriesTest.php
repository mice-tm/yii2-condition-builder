<?php
namespace test\unit;

use micetm\conditions\models\constructor\exceptions\WrongComparison;
use micetm\conditions\models\constructor\attributes\AbstractAttribute;
use micetm\conditions\models\constructor\conditions\Condition;
use micetm\conditions\services\QueryBuilder;
use PHPUnit\Framework\TestCase;

class QueriesTest extends TestCase
{
    /**
     * @dataProvider providerUnarySuccess
     */
    public function testUnaryQuerySuccessCreation(Condition $condition, $expectedQuery)
    {
        $queryBuilder = new QueryBuilder();
        $query = $queryBuilder->create([$condition]);
        $this->assertEquals($query, $expectedQuery);
    }

    public function providerUnarySuccess()
    {
        $now = time();
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
            ],
            [
                'condition' => new Condition([
                    'attribute' => 'start_at',
                    'comparison' => AbstractAttribute::GREATER_THAN_COMPARISON,
                    'value' => $now
                ]),
                'expectedQuery' => [
                    "query" => [
                        "range" => [
                            "start_at" => ["gt" => $now],
                        ]
                    ]
                ],
            ],
            [
                'condition' => new Condition([
                    'attribute' => 'start_at',
                    'comparison' => AbstractAttribute::GREATER_THAN_OR_EQUAL_TO_COMPARISON,
                    'value' => $now
                ]),
                'expectedQuery' => [
                    "query" => [
                        "range" => [
                            "start_at" => ["gte" => $now],
                        ]
                    ]
                ],
            ],
            [
                'condition' => new Condition([
                    'attribute' => 'start_at',
                    'comparison' => AbstractAttribute::LESS_THAN_COMPARISON,
                    'value' => $now
                ]),
                'expectedQuery' => [
                    "query" => [
                        "range" => [
                            "start_at" => ["lt" => $now],
                        ]
                    ]
                ],
            ],
            [
                'condition' => new Condition([
                    'attribute' => 'start_at',
                    'comparison' => AbstractAttribute::LESS_THAN_OR_EQUAL_TO_COMPARISON,
                    'value' => $now
                ]),
                'expectedQuery' => [
                    "query" => [
                        "range" => [
                            "start_at" => ["lte" => $now],
                        ]
                    ]
                ],
            ],
            [
                'condition' => new Condition([
                    'attribute' => 'items.properties.topic',
                    'comparison' => AbstractAttribute::EQUAL_TO_COMPARISON,
                    'value' => ['Cats', 'Animals'],
                ]),
                'expectedQuery' => [
                    "query" => [
                        "bool" => [
                            "should" => [
                                ["terms" => ["items.properties.topic.raw" => ['Cats', 'Animals']]],
                                ["terms" => ["items.properties.topic" => ['Cats', 'Animals']]]
                            ]
                        ]
                    ]
                ],
            ],
            [
                'condition' => new Condition([
                    'attribute' => 'items.properties.topic',
                    'comparison' => AbstractAttribute::MORE_THAN_ONE_IN_COMPARISON,
                    'value' => ['Cats', 'Animals'],
                ]),
                'expectedQuery' => [
                    "query" => [
                        "bool" => [
                            "should" => [
                                ["terms" => ["items.properties.topic.raw" => ['Cats', 'Animals']]],
                                ["terms" => ["items.properties.topic" => ['Cats', 'Animals']]]
                            ]
                        ]
                    ]
                ],
            ],
            [
                'condition' => new Condition([
                    'attribute' => 'items.properties.topic',
                    'comparison' => AbstractAttribute::LIKE_COMPARISON,
                    'value' => 'cats',
                ]),
                'expectedQuery' => [
                    "query" => [
                        "bool" => [
                            "should" => [
                                ["match" => ["items.properties.topic" => 'cats']],
                                ["wildcard" => ["items.properties.topic.raw" => '*cats*']]
                            ]
                        ]
                    ]
                ],
            ],
            [
                'condition' => new Condition([
                    'attribute' => 'items.properties.topic',
                    'comparison' => AbstractAttribute::LIKE_COMPARISON,
                    'value' => 'Cats',
                ]),
                'expectedQuery' => [
                    "query" => [
                        "bool" => [
                            "should" => [
                                ["match" => ["items.properties.topic" => 'Cats']],
                                ["wildcard" => ["items.properties.topic.raw" => '*cats*']]
                            ]
                        ]
                    ]
                ],
            ],
        ];
    }

    public function testUnaryQueryCreationFail()
    {
        $condition = new Condition([
            'attribute' => 'items.id',
            'comparison' => 'not_existing_comparison',
            'value' => 55555
        ]);
        $queryBuilder = new QueryBuilder();
        $this->expectException(WrongComparison::class);
        $queryBuilder->create([$condition]);
    }

    public function testUnaryQueryCreationEmpty1()
    {
        $condition = new Condition([]);
        $queryBuilder = new QueryBuilder();
        $query = $queryBuilder->create([$condition]);
        $this->assertEquals($query, ['query' => null]);
    }

    public function testUnaryQueryCreationEmpty2()
    {
        $queryBuilder = new QueryBuilder();
        $query = $queryBuilder->create(null);
        $this->assertEquals($query, []);
    }

    /**
     * @dataProvider providerComplexQuerySuccessCreation
     */
    public function testComplexQuerySuccessCreation($condition, $expectedQuery)
    {
        $queryBuilder = new QueryBuilder();
        $query = $queryBuilder->create($condition);
        $this->assertEquals($query, $expectedQuery);
    }


    public function providerComplexQuerySuccessCreation()
    {
        $now = time();

        return [
            [
                'condition' => [new Condition([
                    'operator' => Condition::OPERATOR_AND,
                    'conditions' => [
                        [
                            'attribute' => 'start_at',
                            'comparison' => AbstractAttribute::GREATER_THAN_COMPARISON,
                            'value' => $now
                        ],
                        [
                            'attribute' => 'items.properties.topic',
                            'comparison' => AbstractAttribute::LIKE_COMPARISON,
                            'value' => 'cats',
                        ]
                    ]
                ])],
                'expectedQuery' => [
                    "query" => [
                        "bool" => [
                            "must" => [
                                [
                                    "range" => ["start_at" => ["gt" => $now]],
                                ],
                                [
                                    "bool" => [
                                        "should" => [
                                            ["match" => ["items.properties.topic" => 'cats']],
                                            ["wildcard" => ["items.properties.topic.raw" => '*cats*']],
                                        ],
                                    ],
                                ],
                            ]
                        ],
                    ],
                ],
            ],
            [
                'condition' => [
                    new Condition([
                        'attribute' => 'start_at',
                        'comparison' => AbstractAttribute::GREATER_THAN_COMPARISON,
                        'value' => $now
                    ]),
                    new Condition([
                        'attribute' => 'start_at',
                        'comparison' => AbstractAttribute::LESS_THAN_COMPARISON,
                        'value' => $now
                    ]),
                ],
                'expectedQuery' => [
                    "query" => [
                        "bool" => [
                            "should" => [
                                ["range" => ["start_at" => ["gt" => $now]]],
                                ["range" => ["start_at" => ["lt" => $now]]],
                            ]
                        ],
                    ],
                ],
            ],
            [
                'condition' => [new Condition([
                    'operator' => Condition::OPERATOR_AND,
                    'conditions' => [
                        [
                            'attribute' => 'flatConditions',
                            'comparison' => AbstractAttribute::EMBEDDED_COMPARISON,
                            'value' => [
                                "attribute" => "cart.attributes.affiliate",
                                "value" => "promkod",
                            ]
                        ],
                        [
                            'attribute' => 'start_at',
                            'comparison' => AbstractAttribute::LESS_THAN_OR_EQUAL_TO_COMPARISON,
                            'value' => $now,
                        ]
                    ]
                ])],
                'expectedQuery' => [
                    "query" => [
                        "bool" => [
                            "must" => [
                                [
                                    "nested" => [
                                        "path" => "flatConditions",
                                        "score_mode" => "avg",
                                        "query" => [
                                            "bool" => [
                                                "must" => [
                                                    [
                                                        "match" => [
                                                            "flatConditions.attribute" =>
                                                                "cart.attributes.affiliate"
                                                        ]
                                                    ],
                                                    [
                                                        "match" => [
                                                            "flatConditions.value" => "promkod"
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]],
                                [
                                    "range" => [
                                        "start_at" => ["lte" => $now]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
            ],

        ];
    }
}