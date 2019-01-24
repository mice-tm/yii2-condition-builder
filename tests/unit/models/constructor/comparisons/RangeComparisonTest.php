<?php
namespace test\unit\models\constructor\comparisons;

use micetm\conditions\models\constructor\comparisons\RangeComparison;
use micetm\conditions\models\constructor\conditions\Condition;
use PHPUnit\Framework\TestCase;

class RangeComparisonTest extends TestCase
{

    public function testBuildFilterFail()
    {
        $rangeComparison = new RangeComparison();
        $condition = new Condition();
        $this->assertEmpty($rangeComparison->buildFilter($condition));
    }
}
