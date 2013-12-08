<?php

namespace stojg\datamine\tests;

use stojg\datamine\Base;

class BaseTest extends \PHPUnit_Framework_TestCase {
	
	protected $users;
	
	function setUp() {
		$data = file_get_contents(__DIR__. '/fixtures/users.json');
		$this->users = json_decode($data, true);
	}
	
	function testRecommendationHailey() {
		$base = new Base();
		$recommendations = $base->recommend('Hailey', $this->users);
		$this->assertEquals(3, count($recommendations));
		
		$this->assertEquals('Phoenix', $recommendations[0]['key']);
		$this->assertEquals(4.0, $recommendations[0]['value']);
		
		$this->assertEquals('Blues Traveler', $recommendations[1]['key']);
		$this->assertEquals(3.0, $recommendations[1]['value']);
		
		$this->assertEquals('Slightly Stoopid', $recommendations[2]['key']);
		$this->assertEquals(2.5, $recommendations[2]['value']);
	}
	
	function testRecommendationsChan() {
		$base = new Base();
		$recommendations = $base->recommend('Chan', $this->users);
		$this->assertEquals(2, count($recommendations));
		$this->assertEquals('The Strokes', $recommendations[0]['key']);
		$this->assertEquals(5.0, $recommendations[0]['value']);
	}
	
	function testRecommendationsSam() {
		$base = new Base();
		$recommendations = $base->recommend('Sam', $this->users);
		$this->assertEquals(1, count($recommendations));
		$nearest = array_shift($recommendations);
		$this->assertEquals('Deadmau5', $nearest['key']);
		$this->assertEquals(1.0, $nearest['value']);
	}
	
	function testRecommendationsAngelica() {
		$base = new Base();
		$recommendations = $base->recommend('Angelica', $this->users);
		$this->assertEquals(0, count($recommendations));
	}
}
