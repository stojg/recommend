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
