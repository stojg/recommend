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

	/**
	 * 
	 * @param string $item
	 * @param array $set
	 */
	public function __construct($item, $set) {
		$this->item = $item;
		$this->set = $set;
	}

	/**
	 * Give list of recommendations
	 */
	public function recommend($strategy) {
		$nearest = $this->nearestNeighbor($strategy)[0]['key'];
		$recommendations = array();
		$neighborRatings = $this->set[$nearest];
		$userRatings = $this->set[$this->item];
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
	 * creates a sorted list of users based on their distance to username
	 * 
	 */
	protected function nearestNeighbor($strategy) {
		 $distances = array();
		 foreach($this->set as $key => $itemData) {
			 if($key == $this->item) { continue; }
			 $distance = $strategy->run($itemData, $this->set[$this->item]);
			 $distances[] = array('key' => $key, 'value' => $distance);
		 }
		 usort($distances, function($a, $b) {
			 if($a['value'] > $b['value']) { return 1; }
			 if($a['value'] < $b['value']) { return -1; }
			 return 0;
		 });
		 return $distances;
	}
}
