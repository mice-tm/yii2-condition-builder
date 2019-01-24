<?php
namespace test\unit\models\constructor\services;

use micetm\conditions\models\constructor\attributes\AbstractAttribute;
use micetm\conditions\models\constructor\conditions\Condition;
use micetm\conditions\services\OracleService;
use PHPUnit\Framework\TestCase;

class OracleServiceTest extends TestCase
{
    /**
     * @var OracleService
     */
    protected $oracle;

    public function setUp()
    {
        $this->oracle = new OracleService();
    }

    /**
     * @skip
     * @param $condition
     *
     * @param $expectedResult
     * @dataProvider providerSpeakSuccess
     */
    public function testSpeakSuccess($condition, $expectedResult)
    {
        $target = [
            'key1' => 'value1',
            'key2' => 'value2',
            'embeddedKey1' => [
                [
                    'key1' => 'value1',
                    'key2' => 'value2',
                ],
                [
                    'key3' => 'value3',
                    'key4' => 'value4',
                ]
            ],
            'embeddedKey2' => [
                [
                    'key5' => 'value5',
                    'key6' => 'value6',
                ],
                [
                    'key5' => 'value5',
                    'key6' => 'value6',
                ]
            ],
        ];
        $this->assertEquals($expectedResult, $this->oracle->speak($condition, $target));
    }

    public function providerSpeakSuccess()
    {
        return [
            [
                'condition' => new Condition([
                    'attribute' => 'key1',
                    'comparison' => AbstractAttribute::EQUAL_TO_COMPARISON,
                    'value' => 'value1'
                ]),
                true
            ],
            [
                'condition' => new Condition([
                    'attribute' => 'unknownAttribute',
                    'comparison' => AbstractAttribute::EQUAL_TO_COMPARISON,
                    'value' => 'value1'
                ]),
                false
            ],
            [
                'condition' => new Condition([
                    'attribute' => 'embeddedKey1.1.key3',
                    'comparison' => AbstractAttribute::EQUAL_TO_COMPARISON,
                    'value' => 'value3'
                ]),
                true
            ],
            [
                'condition' => new Condition([
                    'operator' => Condition::OPERATOR_NOT,
                    'conditions' => [
                        [
                            'attribute' => 'key1',
                            'comparison' => AbstractAttribute::EQUAL_TO_COMPARISON,
                            'value' => 'value1'
                        ]
                    ]
                ]),
                false
            ],
            [
                'condition' => new Condition([
                    'operator' => Condition::OPERATOR_NOT,
                    'conditions' => [
                        [
                            'attribute' => 'key2',
                            'comparison' => AbstractAttribute::EQUAL_TO_COMPARISON,
                            'value' => 'value1'
                        ]
                    ]
                ]),
                true
            ],
            [
                'condition' => new Condition([
                    'operator' => Condition::OPERATOR_AND,
                    'conditions' => [
                        [
                            'attribute' => 'key1',
                            'comparison' => AbstractAttribute::EQUAL_TO_COMPARISON,
                            'value' => 'value1'
                        ],
                        [
                            'attribute' => 'embeddedKey1.1.key3',
                            'comparison' => AbstractAttribute::EQUAL_TO_COMPARISON,
                            'value' => 'value3'
                        ],
                    ]
                ]),
                true
            ],
            [
                'condition' => new Condition([
                    'operator' => Condition::OPERATOR_AND,
                    'conditions' => [
                        [
                            'attribute' => 'key1',
                            'comparison' => AbstractAttribute::EQUAL_TO_COMPARISON,
                            'value' => 'value1'
                        ],
                        [
                            'attribute' => 'embeddedKey1.1.key2',
                            'comparison' => AbstractAttribute::EQUAL_TO_COMPARISON,
                            'value' => 'value3'
                        ],
                    ]
                ]),
                false
            ],
            [
                'condition' => new Condition([
                    'operator' => Condition::OPERATOR_OR,
                    'conditions' => [
                        [
                            'attribute' => 'key1',
                            'comparison' => AbstractAttribute::EQUAL_TO_COMPARISON,
                            'value' => 'value1'
                        ],
                        [
                            'attribute' => 'embeddedKey1.1.key3',
                            'comparison' => AbstractAttribute::EQUAL_TO_COMPARISON,
                            'value' => 'value3'
                        ],
                    ]
                ]),
                true
            ],
            [
                'condition' => new Condition([
                    'operator' => Condition::OPERATOR_OR,
                    'conditions' => [
                        [
                            'attribute' => 'key1',
                            'comparison' => AbstractAttribute::EQUAL_TO_COMPARISON,
                            'value' => 'value1'
                        ],
                        [
                            'attribute' => 'embeddedKey1.1.key2',
                            'comparison' => AbstractAttribute::EQUAL_TO_COMPARISON,
                            'value' => 'value3'
                        ],
                    ]
                ]),
                true
            ],
            [
                'condition' => new Condition([
                    'operator' => Condition::OPERATOR_OR,
                    'conditions' => [
                        [
                            'attribute' => 'key2',
                            'comparison' => AbstractAttribute::EQUAL_TO_COMPARISON,
                            'value' => 'value1'
                        ],
                        [
                            'attribute' => 'embeddedKey1.1.key2',
                            'comparison' => AbstractAttribute::EQUAL_TO_COMPARISON,
                            'value' => 'value3'
                        ],
                    ]
                ]),
                false
            ],
        ];
    }
}
