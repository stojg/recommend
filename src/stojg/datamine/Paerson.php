<?php

namespace stojg\datamine;

/**
 * Use if the data is subject to grade-inﬂation (different users may be using different scales) 
 *
 */
class Paerson {
	
	/**
	 * Single pass version of the paerson
	 * 
	 * @param array $ratings1
	 * @param array  $ratings2
	 * @return float
	 */
	public function run($ratings1, $ratings2) {
		$coRatedItems = 0;
		$combinedSum = 0;
		$rating1Sum = 0;
		$rating1SumSqr = 0;
		$rating2Sum = 0;
		$rating2SumSqr = 0;
		foreach($ratings1 as $item => $rating) {
			if(!isset($ratings2[$item])) {
				continue;
			}
			$coRatedItems += 1;
			$combinedSum += $ratings1[$item] * $ratings2[$item];
			$rating1Sum += $ratings1[$item];
			$rating1SumSqr += pow($ratings1[$item], 2);
			$rating2Sum += $ratings2[$item];
			$rating2SumSqr += pow($ratings2[$item], 2); 
		}
		
		if($coRatedItems == 0) {
			return 0;
		}
		
		$denominator = sqrt(
			($rating1SumSqr - (pow($rating1Sum, 2)/$coRatedItems)) * 
			($rating2SumSqr - (pow($rating2Sum, 2)/$coRatedItems))
		);
		
		if($denominator == 0) {
			return 0;
		}
		
		$paerson = $combinedSum - ( ( $rating1Sum * $rating2Sum) / $coRatedItems);
		$paerson /= $denominator;
		return $paerson;
	}
}
