<?php

namespace stojg\datamine\tests;

use stojg\datamine\Recommender;
use stojg\datamine\Manhattan;

class RecommenderTest extends \PHPUnit_Framework_TestCase {
	
	protected $set;
	
	function setUp() {
		$data = file_get_contents(__DIR__. '/fixtures/users.json');
		$this->set = json_decode($data, true);
	}
	
	function testRecommendationHailey() {
		$base = new Recommender('Hailey', $this->set);
		$recommendations = $base->recommend(new Manhattan());
		$this->assertEquals(3, count($recommendations));
		$this->assertEquals('Phoenix', $recommendations[0]['key']);
		$this->assertEquals(4.0, $recommendations[0]['value']);
		$this->assertEquals('Blues Traveler', $recommendations[1]['key']);
		$this->assertEquals(3.0, $recommendations[1]['value']);
		$this->assertEquals('Slightly Stoopid', $recommendations[2]['key']);
		$this->assertEquals(2.5, $recommendations[2]['value']);
	}
	
	function testRecommendationsChan() {
		$base = new Recommender('Chan', $this->set);
		$recommendations = $base->recommend(new Manhattan());
		$this->assertEquals(2, count($recommendations));
		$this->assertEquals('The Strokes', $recommendations[0]['key']);
		$this->assertEquals(5.0, $recommendations[0]['value']);
	}
	
	function testRecommendationsSam() {
		$base = new Recommender('Sam', $this->set);
		$recommendations = $base->recommend(new Manhattan());
		$this->assertEquals(1, count($recommendations));
		$nearest = array_shift($recommendations);
		$this->assertEquals('Deadmau5', $nearest['key']);
		$this->assertEquals(1.0, $nearest['value']);
	}
	
	function testRecommendationsAngelica() {
		$base = new Recommender('Angelica', $this->set);
		$recommendations = $base->recommend(new Manhattan());
		$this->assertEquals(0, count($recommendations));
	}
}
