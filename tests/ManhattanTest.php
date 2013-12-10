<?php

namespace stojg\datamine\tests;

use stojg\datamine\Manhattan;

class ManhattanTest extends \PHPUnit_Framework_TestCase {
	
	protected $set;
	
	function setUp() {
		$data = file_get_contents(__DIR__. '/fixtures/users.json');
		$this->set = json_decode($data, true);
	}
	
	function testManhattanAngelicaBill() {
		$manhattan = new Manhattan();
		$score = $manhattan->run($this->set['Angelica'], $this->set['Bill']);
		$this->assertEquals(9.0, $score);
	}
	
	
}
