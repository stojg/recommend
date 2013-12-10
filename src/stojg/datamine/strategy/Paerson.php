<?php

namespace stojg\datamine\strategy;

/**
 * Use if the data is subject to grade-inﬂation (different users may be using different scales) 
 *
 */
class Paerson
{

    /**
     * Single pass version of the paerson
     * 
     * @param array $ratings1
     * @param array  $ratings2
     * @return float
     */
    public function run($ratings1, $ratings2)
    {
        $numCoRatedItems = 0;
        $dotProduct = 0;
        $rating1Sum = 0;
        $rating1SumSqr = 0;
        $rating2Sum = 0;
        $rating2SumSqr = 0;
        foreach ($ratings1 as $item => $rating) {
            if (!isset($ratings2[$item])) {
                continue;
            }
            $numCoRatedItems += 1;
            $dotProduct += $ratings1[$item] * $ratings2[$item];
            $rating1Sum += $ratings1[$item];
            $rating1SumSqr += pow($ratings1[$item], 2);
            $rating2Sum += $ratings2[$item];
            $rating2SumSqr += pow($ratings2[$item], 2);
        }

        // There is no correlation at all
        if ($numCoRatedItems == 0) {
            return false;
        }

        $denominator = sqrt(
            ($rating1SumSqr - (pow($rating1Sum, 2) / $numCoRatedItems)) *
            ($rating2SumSqr - (pow($rating2Sum, 2) / $numCoRatedItems))
        );

        if ($denominator == 0) {
            return false;
        }

        // the closer abs(paerson) is to 1 to better correlation is it
        $paerson = ( $dotProduct - ( $rating1Sum * $rating2Sum / $numCoRatedItems)) / $denominator;
        return 1 - abs($paerson);
    }
}
