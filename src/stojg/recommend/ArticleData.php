<?php

namespace stojg\recommend;

use stojg\recommend\strategy\Cosine;

/**
 * Description of ArticleData
 *
 */
class ArticleData extends Data
{

    /**
     *
     * @var array
     */
    protected $stopWords = array("a", "about", "above", "above", "across", "after", "afterwards", "again", "against",
        "all", "almost", "alone", "along", "already", "also", "although", "always", "am", "among", "amongst",
        "amoungst", "amount", "an", "and", "another", "any", "anyhow", "anyone", "anything", "anyway", "anywhere",
        "are", "around", "as", "at", "back", "be", "became", "because", "become", "becomes", "becoming", "been",
        "before", "beforehand", "behind", "being", "below", "beside", "besides", "between", "beyond", "bill", "both",
        "bottom", "but", "by", "call", "can", "cannot", "cant", "co", "con", "could", "couldnt", "cry", "de",
        "describe", "detail", "do", "done", "down", "due", "during", "each", "eg", "eight", "either", "eleven", "else",
        "elsewhere", "empty", "enough", "etc", "even", "ever", "every", "everyone", "everything", "everywhere",
        "except", "few", "fifteen", "fify", "fill", "find", "fire", "first", "five", "for", "former", "formerly",
        "forty", "found", "four", "from", "front", "full", "further", "get", "give", "go", "had", "has", "hasnt",
        "have", "he", "hence", "her", "here", "hereafter", "hereby", "herein", "hereupon", "hers", "herself", "him",
        "himself", "his", "how", "however", "hundred", "i'd", "ie", "if", "in", "inc", "indeed", "interest", "into",
        "is", "it", "its", "itself", "keep", "last", "latter", "latterly", "least", "less", "ltd", "made", "many",
        "may", "me", "meanwhile", "might", "mill", "mine", "more", "moreover", "most", "mostly", "move", "much", "must",
        "my", "myself", "name", "namely", "neither", "never", "nevertheless", "next", "nine", "no", "nobody", "none",
        "noone", "nor", "not", "nothing", "now", "nowhere", "of", "off", "often", "on", "once", "one", "only", "onto",
        "or", "other", "others", "otherwise", "our", "ours", "ourselves", "out", "over", "own", "part", "per",
        "perhaps", "please", "put", "rather", "re", "same", "see", "seem", "seemed", "seeming", "seems", "serious",
        "several", "she", "should", "show", "side", "since", "sincere", "six", "sixty", "so", "some", "somehow",
        "someone", "something", "sometime", "sometimes", "somewhere", "still", "such", "system", "take", "ten", "than",
        "that", "the", "their", "them", "themselves", "then", "thence", "there", "thereafter", "thereby", "therefore",
        "therein", "thereupon", "these", "they", "thickv", "thin", "third", "this", "those", "though", "three",
        "through", "throughout", "thru", "thus", "to", "together", "too", "top", "toward", "towards", "twelve",
        "twenty", "two", "un", "under", "until", "up", "upon", "us", "very", "via", "was", "we", "well", "were", "what",
        "whatever", "when", "whence", "whenever", "where", "whereafter", "whereas", "whereby", "wherein", "whereupon",
        "wherever", "whether", "which", "while", "whither", "who", "whoever", "whole", "whom", "whose", "why", "will",
        "with", "within", "without", "would", "yet", "you", "your", "yours", "yourself", "yourselves", "the");

    /**
     * 
     * @param array $set
     */
    public function __construct()
    {
        // Noop - We want to start off with a clean set and use the push method to add
        // data to the set
    }

    /**
     * 
     * @param string $identifier
     * @param string $content
     */
    public function push($identifier, $content)
    {
        $this->set[$identifier] = $this->getWordCount($content);
    }

    /**
     * 
     * @param string $for
     * @param Object $strategy
     */
    public function findNearest($for, $strategy = null)
    {
        if ($strategy === null) {
            $strategy = new Cosine();
        }
        return parent::findNearest($for, $strategy);
    }

    /**
     * Get an array of words from the content and a count of how many times
     * they appear in the text.
     * 
     * Note that this method is naive and can't tell the similarity between 'bird' and 'birds'.
     * 
     * @param string $content
     * @return array
     */
    protected function getWordCount($content)
    {
        $content = strip_tags(strtolower((preg_replace('/\s+/', ' ', $content))));
        $words = str_word_count($content, 1);
        $filteredWords = array_diff($words, $this->stopWords);
        $countedWords = array_count_values($filteredWords);
        ksort($countedWords);
        return $countedWords;
    }
}
