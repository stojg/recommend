<?php

namespace stojg\recommend;

/**
 * This class contains behaviour for finding recommendations
 *
 */
class Data
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
     * @param array $set - The full dataset
     */
    public function __construct($set)
    {
        $this->set = $set;
    }

    /**
     * Return a list of recommendations
     *
     * @param string $for - the item we want recommendations for
     * @param Object $strategy
     * @return array
     */
    public function recommend($for, $strategy)
    {
        $nearest = $this->findNearest($for, $strategy);
        if ($nearest === false) {
            return array();
        }
        $recommendations = array();
        foreach ($this->set[$nearest] as $item => $rating) {
            // The item has been already been rated
            if (isset($this->set[$for][$item])) {
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
    public function findNearest($for, $strategy)
    {
        $distances = array();
        foreach ($this->set as $key => $itemData) {
            if ($key == $for) {
                continue;
            }
            $distance = $strategy->run($itemData, $this->set[$for]);
            if ($distance === false) {
                continue;
            }
            $distances[] = array('key' => $key, 'value' => $distance);
        }
        if (!count($distances)) {
            return false;
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
        usort($distances, function ($first, $second) use ($ascending) {
            if ($first['value'] > $second['value']) {
                return ($ascending) ? 1 : -1;
            } elseif ($first['value'] < $second['value']) {
                return ($ascending) ? -1 : 1;
            }
            return 0;
        });
    }
}
