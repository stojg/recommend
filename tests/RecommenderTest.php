<?php

namespace stojg\recommend\tests;

use stojg\recommend\Data;
use stojg\recommend\strategy\Manhattan;
use stojg\recommend\strategy\Paerson;
use stojg\recommend\strategy\Cosine;

class DataTest extends \PHPUnit_Framework_TestCase
{

    protected $set;

    public function setUp()
    {
        $data = file_get_contents(__DIR__ . '/fixtures/users.json');
        $this->set = json_decode($data, true);
    }

    public function testRecommendManhattanHailey()
    {
        $data = new Data($this->set);
        $recommendations = $data->recommend('Hailey', new Manhattan());
        $this->assertEquals(3, count($recommendations));
        $this->assertEquals('Phoenix', $recommendations[0]['key']);
        $this->assertEquals(4.0, $recommendations[0]['value']);
        $this->assertEquals('Blues Traveler', $recommendations[1]['key']);
        $this->assertEquals(3.0, $recommendations[1]['value']);
        $this->assertEquals('Slightly Stoopid', $recommendations[2]['key']);
        $this->assertEquals(2.5, $recommendations[2]['value']);
    }

    public function testRecommendManhattanChan()
    {
        $data = new Data($this->set);
        $recommendations = $data->recommend('Chan', new Manhattan());
        $this->assertEquals(2, count($recommendations));
        $this->assertEquals('The Strokes', $recommendations[0]['key']);
        $this->assertEquals(5.0, $recommendations[0]['value']);
    }

    public function testRecommendManhattanSam()
    {
        $data = new Data($this->set);
        $recommendations = $data->recommend('Sam', new Manhattan());
        $this->assertEquals(1, count($recommendations));
        $this->assertEquals('Deadmau5', $recommendations[0]['key']);
        $this->assertEquals(1.0, $recommendations[0]['value']);
    }

    public function testRecommendManhattanAngelica()
    {
        $data = new Data($this->set);
        $recommendations = $data->recommend('Angelica', new Manhattan());
        $this->assertEquals(0, count($recommendations));
    }

    public function testRecommendPaersonHailey()
    {
        $data = new Data($this->set);
        $recommendations = $data->recommend('Hailey', new Paerson());
        $this->assertEquals(3, count($recommendations));
        $this->assertEquals('Blues Traveler', $recommendations[0]['key']);
        $this->assertEquals(5.0, $recommendations[0]['value']);
        $this->assertEquals('Phoenix', $recommendations[1]['key']);
        $this->assertEquals(5.0, $recommendations[1]['value']);
        $this->assertEquals('Slightly Stoopid', $recommendations[2]['key']);
        $this->assertEquals(4.0, $recommendations[2]['value']);
    }

    public function testRecommendPaersonNoMatch()
    {
        $set = json_decode(file_get_contents(__DIR__ . '/fixtures/users_nomatch.json'), true);
        $data = new Data($set);
        $recommendations = $data->recommend('Andrea', new Paerson());
        $this->assertEquals(0, count($recommendations));
    }

    public function testRecommendCosineHailey()
    {
        $data = new Data($this->set);
        $recommendations = $data->recommend('Hailey', new Cosine());
        $this->assertEquals(3, count($recommendations));
        $this->assertEquals('Blues Traveler', $recommendations[0]['key']);
        $this->assertEquals(5.0, $recommendations[0]['value']);
        $this->assertEquals('Phoenix', $recommendations[1]['key']);
        $this->assertEquals(5.0, $recommendations[1]['value']);
    }

    public function testFromReadme()
    {
        $artistRatings = array(
            "Abe" => array(
                "Blues Traveler" => 3,
                "Broken Bells" => 2,
                "Norah Jones" => 4,
                "Phoenix" => 5,
                "Slightly Stoopid" => 1,
                "The Strokes" => 2,
                "Vampire Weekend" => 2
            ),
            "Blair" => array(
                "Blues Traveler" => 2,
                "Broken Bells" => 3,
                "Deadmau5" => 4,
                "Phoenix" => 2,
                "Slightly Stoopid" => 3,
                "Vampire Weekend" => 3
            ),
            "Clair" => array(
                "Blues Traveler" => 5,
                "Broken Bells" => 1,
                "Deadmau5" => 1,
                "Norah Jones" => 3,
                "Phoenix" => 5,
                "Slightly Stoopid" => 1
            )
        );

        $data = new \stojg\recommend\Data($artistRatings);
        $recommendations = $data->recommend('Blair', new \stojg\recommend\strategy\Manhattan());
        $this->assertEquals('Norah Jones', $recommendations[0]['key']);
        $this->assertEquals(4, $recommendations[0]['value']);
    }

    public function testWithArticles()
    {
        $data = new \stojg\recommend\ArticleData();
        $data->push('eagle', file_get_contents(__DIR__ . '/fixtures/article_eagle.txt'));
        $data->push('kiwi', file_get_contents(__DIR__ . '/fixtures/article_kiwi.txt'));
        $data->push('turtle', file_get_contents(__DIR__ . '/fixtures/article_turtle.txt'));
        $data->push('quantum', file_get_contents(__DIR__ . '/fixtures/article_quantum.txt'));

        $eagleNearest = $data->findNearest('eagle');
        $this->assertEquals('kiwi', $eagleNearest, 'readers of eagle should read kiwi');

        $kiwiNearest = $data->findNearest('kiwi');
        $this->assertEquals('eagle', $kiwiNearest, 'readers of kiwi should read eagle');

        $turtleNearest = $data->findNearest('turtle');
        $this->assertEquals('eagle', $turtleNearest, 'turtle of eagle should read eagle');

        $quantumNearest = $data->findNearest('quantum');
        $this->assertEquals('turtle', $quantumNearest, 'readers of quantum should read turtle');
    }
}
