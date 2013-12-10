<?php

namespace stojg\datamine;

class Recommender {
	
	/**
	 *
	 * @var array
	 */
	protected  $item = '';
	
	/**
	 *
	 * @var array
	 */
	protected $set = array();


	public function __construct($item, $set) {
		$this->item = $item;
		$this->set = $set;
	}

	/**
	 * Give list of recommendations
	 */
	public function recommend() {
		$username = $this->item;
		$users = $this->set;
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
	 * Single pass version of the paerson
	 * 
	 * Use if the data is subject to grade-inﬂation (different users 
	 * may be using different scales) 
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
	 * Use if the data is sparse 
	 */
	public function cosin($rating1, $rating2) {
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
	 * Computes the Minkowski distance
	 * 
	 * If the data is dense (allmost all attributes have a non
	 * zero value) and the magnitude of the attributes values
	 * are important, this is a good similarity comparisator
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
	
}
