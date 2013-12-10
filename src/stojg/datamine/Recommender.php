<?php

namespace stojg\datamine;

/**
 * This class contains behaviour for finding recommendations 
 * 
 */
class Recommender
{

    /**
     * This the 
     * 
     * @var array
     */
    protected $item = '';

    /**
     * This is the full dataset
     *
     * @var array
     */
    protected $set = array();

    /**
     * 
     * @param string $item - a key from the set that we would like to find the best recommendations
     * @param array $set - The full dataset
     */
    public function __construct($item, $set)
    {
        $this->item = $item;
        $this->set = $set;
    }

    /**
     * Give a list of recommendations
     */
    public function recommend($strategy)
    {
        $nearest = $this->findNearest($strategy);
        if ($nearest === false) {
            return array();
        }
        $recommendations = array();
        foreach ($this->set[$nearest] as $item => $rating) {
            // The item has been already been rated
            if (isset($this->set[$this->item][$item])) {
                continue;
            }
            $recommendations[] = array('key' => $item, 'value' => $rating);
        }
        $this->sort($recommendations, false);
        return $recommendations;
    }

    /**
     * Find the nearest key that matching is closest to the item in the set
     * 
     * @return string 
     */
    protected function findNearest($strategy)
    {
        $distances = array();
        foreach ($this->set as $key => $itemData) {
            if ($key == $this->item) {
                continue;
            }
            $distance = $strategy->run($itemData, $this->set[$this->item]);
            $distances[] = array('key' => $key, 'value' => $distance);
        }
        $this->sort($distances, true);
        return $distances[0]['key'];
    }

    /**
     * sort an nested array that have a value attribute
     * 
     * i.e [ 0 => [ 'value' => 5 ], 1 => [ 'value' => 2 ] ]
     * 
     * @param array $distances
     * @param bool $ascending
     */
    protected function sort(&$distances, $ascending = true)
    {
        usort($distances, function ($a, $b) use ($ascending) {
            if ($a['value'] > $b['value']) {
                return ($ascending) ? 1 : -1;
            } elseif ($a['value'] < $b['value']) {
                return ($ascending) ? -1 : 1;
            }
            return 0;
        });
    }
}
