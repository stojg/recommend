# Data mine

This library makes it easy to find recommendations and similarities between different sets. One use case would be
to recommend a list of music albums/artists to a user or an article that is similar to the current one the user is reading.

## Installation

The easiest way to get this installed in your project is by using composer

	composer install stojg/datamine

## Usage

Presume that we have a set of data where users have rated artists within a scale of one to five:

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

If we wanted to find out which artists that Blair might like, we run the recommender like this:

	$recommender = new \stojg\datamine\Recommender('Blair', $artistRatings);
	$recommendations = $recommender->recommend(new \stojg\datamine\strategy\Manhattan());
	var_export($recommendations);

The result of that computation would be:

	array (
	  0 => array (
		'key' => 'Norah Jones',
		'value' => 4,
	  ),
	  1 => array (
		'key' => 'The Strokes',
		'value' => 2,
	  )
	)

This means that Blair probably should try Norah Jones, she might like it. The Strokes will probably not be to her 
liking.

The Recommender tries to find someone in the $artistRatings data that have rated artist similar to to Blair. In this 
case it turns out to be Abe, so it then tries to find artists that Abe have rated but not Blair and give them back as 
recommendations.

How the 'nearest' neighbour is found depends on which strategy that is chosen and how big and dense the dataset is.

## Dataset

The general rule is that the bigger the dataset is, the better. It have to be formatted as an array in the following
format:

	array(
		'uniqueID' => array(
			'objectID' => (int)'rating'
		)
	);

## Strategies

There currently three (four, depending how you are counting) strategies and which one to pick depends on how the data is
organised.

### Minkowski

If the data is dense (almost all attributes have a non zero value) and the magnitude (rating) of the attributes values
are important, this is a good similarity comparisator.

It can be have a defined dimenstion from 1 and up. The bigger the dimenstion is, the bigger the difference between the
"score" will be.

### Manhattan

Manhattan is a shortcut for a Minkowski with a dimension of one.

### Paerson

Use if the data is subject to grade-inﬂation. This means that the I might rate most items between 2 to 4, where as you 
rate most things between 4-5 and Glenn is all over the board with rating from 1 to 5.

This similarity algorithm tries to compensate the fact that my 2 is equal to your 4 in rating.

### Cosine

This is the strategy to pick if the data is sparse. That is if you have a lot or artists in the dataset that hasn't been
rated at all by many users. If you have a list with ten thousand artist, it quiet likely that the users only listened
and rated a few of them.

It basically disregard the null values so they don't influence the similarity score.


