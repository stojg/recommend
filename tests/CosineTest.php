<?php

namespace stojg\datamine\tests;

use stojg\datamine\Cosine;

class CosineTest extends \PHPUnit_Framework_TestCase {
	
	function testCosinSimilarity() {
		$cosin = new Cosine();
		$users = json_decode(file_get_contents(__DIR__. '/fixtures/perfect_users.json'), true);
		$score = $cosin->run($users['Clara'], $users['Robert']);
		$this->assertEquals(0.93515345857052, $score);
	}
}