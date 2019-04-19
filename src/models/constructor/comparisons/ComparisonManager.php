<?php
namespace micetm\conditions\models\constructor\comparisons;

use micetm\conditionsBase\exceptions\WrongComparison;
use micetm\conditionsBase\models\ComparisonInterface;
use micetm\conditions\models\constructor\comparisons\aggregations\SizeComparison;
use micetm\conditions\models\constructor\conditions\Condition;

class ComparisonManager
{
    const AVAILABLE_COMPARISONS = [
        SizeComparison::class,
        RangeComparison::class,
        LikeComparison::class,
        InComparison::class,
        DefaultComparison::class,
        EmbeddedComparison::class
    ];

    /**
     * @param Condition $condition
     * @return ComparisonInterface
     * @throws WrongComparison
     */
    public function getComparison(Condition $condition): ComparisonInterface
    {
        foreach (self::AVAILABLE_COMPARISONS as $className) {
            if ($className::isMaster($condition)) {
                return new $className();
            }
        }

        throw new WrongComparison();
    }
}
