<?php

namespace stojg\datamine;

class Base {

	/**
	 * Give list of recommendations
	 */
	public function recommend($username, $users) {
		$nearest = $this->nearestNeighbor($username, $users)[0]['key'];
		$recommendations = array();
		$neighborRatings = $users[$nearest];
		$userRatings = $users[$username];
		foreach($neighborRatings as $item => $rating) {
			if(isset($userRatings[$item])) { continue; }
			$recommendations[] = array('key' => $item, 'value' => $rating);
		}
		usort($recommendations, function($a, $b) {
			 if($a['value'] < $b['value']) { return 1; }
			 if($a['value'] > $b['value']) { return -1; }
			 return 0;
		 });
		return $recommendations;
	}

	/**
	 * Single pass version of the paerson koeefi
	 * 
	 * @param array $ratings1
	 * @param array  $ratings2
	 * @return float
	 */
	public function paerson($ratings1, $ratings2) {
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
	
	/**
	 * Computes the Manhattan distance. 
	 * 
	 * Both rating1 and rating2 are an array of the form
	 * ['The Strokes'=> 3.0, 'Slightly Stoopid' =>  2.5, ...]
	 * 
	 * @param array $rating1
	 * @param array $rating2
	 */
	protected function manhattanDistance($rating1, $rating2) {
		return $this->minkowski($rating1, $rating2, 1);
	}
	
	/**
	 * creates a sorted list of users based on their distance to username
	 * 
	 */
	protected function nearestNeighbor($username, $users) {
		 $distances = array();
		 foreach($users as $user => $data) {
			 if($user == $username) { continue; }
			 $distance = $this->manhattanDistance($users[$user], $users[$username]);
			 $distances[] = array('key' => $user, 'value' => $distance);
		 }
		 usort($distances, function($a, $b) {
			 if($a['value'] > $b['value']) { return 1; }
			 if($a['value'] < $b['value']) { return -1; }
			 return 0;
		 });
		 return $distances;
	}
	
	/**
	 * Computes the Minkowski distance
	 * 
	 * Both rating1 and rating2 are an array of the form
	 * ['The Strokes'=> 3.0, 'Slightly Stoopid' =>  2.5, ...]
	 * 
	 * @param array $rating1
	 * @param array $rating2
	 * @param int $r
	 */
	protected function minkowski($rating1, $rating2, $r) {
		$distance = 0;
		$commonRatings = false;
		foreach($rating1 as $key => $rating) {
			if(isset($rating2[$key])) {
				$distance += pow(abs($rating1[$key] - $rating2[$key]),$r);
				$commonRatings = true;
			}
		}
		if($commonRatings) {
			return pow($distance, 1/$r);
		} 
		return 0;
	}
}
