<?php

namespace stojg\datamine\tests;

use stojg\datamine\Recommender;

class RecommenderTest extends \PHPUnit_Framework_TestCase {
	
	protected $set;
	
	function setUp() {
		$data = file_get_contents(__DIR__. '/fixtures/users.json');
		$this->set = json_decode($data, true);
	}
	
	function testRecommendationHailey() {
		$base = new Recommender('Hailey', $this->set);
		$recommendations = $base->recommend();
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
		$recommendations = $base->recommend();
		$this->assertEquals(2, count($recommendations));
		$this->assertEquals('The Strokes', $recommendations[0]['key']);
		$this->assertEquals(5.0, $recommendations[0]['value']);
	}
	
	function testRecommendationsSam() {
		$base = new Recommender('Sam', $this->set);
		$recommendations = $base->recommend();
		$this->assertEquals(1, count($recommendations));
		$nearest = array_shift($recommendations);
		$this->assertEquals('Deadmau5', $nearest['key']);
		$this->assertEquals(1.0, $nearest['value']);
	}
	
	function testRecommendationsAngelica() {
		$base = new Recommender('Angelica', $this->set);
		$recommendations = $base->recommend();
		$this->assertEquals(0, count($recommendations));
	}
	
	function testPaersonAngelicaBill() {
		$base = new Recommender('Angelica', $this->set);
		$pearson = $base->paerson($this->set['Angelica'], $this->set['Bill']);
		$this->assertEquals(-0.90405349906826993, $pearson);
	}
	
	function testPaersonAngelicaHaily() {
		$base = new Recommender('Angelica', $this->set);
		$pearson = $base->paerson($this->set['Angelica'], $this->set['Hailey']);
		$this->assertEquals(0.42008402520840293, $pearson);
	}
	
	function testPaersonAngelicaJordyn() {
		$base = new Recommender('Angelica', $this->set);
		$pearson = $base->paerson($this->set['Angelica'], $this->set['Jordyn']);
		$this->assertEquals(0.76397486054754316, $pearson);
	}
	
	function testPaersonNoMatch() {
		$base = new Recommender('Angelica', $this->set);
		$pearson = $base->paerson($this->set['Angelica'], $this->set['Bosse']);
		$this->assertEquals(0, $pearson);
	}
	
	function testCosinSimilarity() {
		$base = new Recommender('Clara', $this->set);
		$users = json_decode(file_get_contents(__DIR__. '/fixtures/perfect_users.json'), true);
		$result = $base->cosin($users['Clara'], $users['Robert']);
		$this->assertEquals(0.93515345857052, $result);
	}
}
