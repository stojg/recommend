<?php

namespace stojg\datamine;

/**
 * Description of Cosin
 *
 */
class Cosin {
	
	/**
	 * Use if the data is sparse 
	 */
	public function run($rating1, $rating2) {
		$dotProduct = 0;
		$sqrLenght1 = 0;
		foreach($rating1 as $item => $rating) {
			$sqrLenght1 += pow($rating, 2);
			$dotProduct += $rating * $rating2[$item];
		}
		$length1 = sqrt($sqrLenght1);
		
		$sqrLength2 = 0;
		foreach($rating2 as $item => $rating) {
			$sqrLength2 += pow($rating, 2);
		}
		$length2 = sqrt($sqrLength2);
		
		return $dotProduct / ($length1 * $length2);
	}
	
}
