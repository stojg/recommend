# Recommend

[![Build Status](https://travis-ci.org/stojg/recommend.png?branch=master)](https://travis-ci.org/stojg/recommend)
[![Code Coverage](https://scrutinizer-ci.com/g/stojg/recommend/badges/coverage.png?s=5938cb4642b77c2ea081f4771f096134b93d3494)](https://scrutinizer-ci.com/g/stojg/recommend/)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/stojg/recommend/badges/quality-score.png?s=ccc1fe675b9e51fc87694d5a09b509bf0d1352f9)](https://scrutinizer-ci.com/g/stojg/recommend/)

This library should make it easier to find recommendations and similarities between different things. There are a couple of 
use cases that I developed it for:
 
  * Recommend a list of music albums/artists to a user
  * Recommend an article that is similar to the current one that a user is reading
  * Find other users that have the same values as another user (think matchmaking ;)

## Installation

The easiest way to get this installed in your project is by using composer

	$ composer require stojg/recommend

## Usage

Assuming that we have some data where users have rated music artists within a scale of one to five:

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

We then load this data into the Data class

	$data = new \stojg\recommend\Data($artistRatings);

If we want to find artists that _Blair_ might like, we execute the recommend method.

	$recommendations = $data->recommend('Blair', new \stojg\recommend\strategy\Manhattan());
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

This means that _Blair_ might like `Norah Jones` and not like `The Strokes`.

The `Recommender` works by finding someone in the `$artistRatings` that have rated artist similar to to _Blair_. In this 
case it turns out to be _Abe_, so it then tries to find artists that _Abe_ have rated but not _Blair_ and return them 
as a list of recommendations.

How the 'nearest neighbour' is found depends on which strategy that is chosen and how big and dense the dataset is.

## The Dataset

The general rule is that the bigger the dataset is, the better. It have to be formatted as an array in the following
format:

	array(
		'uniqueID' => array(
			'objectID' => (int)'rating'
		)
	);

Where in the case of the previous artist rating example 

    * uniqueID = Blair
    * objectID = Music Artist
    * rating = an numeric value

## Strategies

There are currently three strategies and which one to pick depends on how the data is organized and populated.

### Manhattan

If the data is dense (almost all `objectID`s in the full data set have a non null rating) and the magnitude (rating) of the attributes values
are important, this is a good strategy.

I.e. all users have rated all music artists and they all agree on the same scale.

### Paerson

Use this strategy if the data is dense but the ratings are subject to grade-inflation.

I.e. if user A have rated all artists between 2-4 and user B have rated artists between 4-5 this strategy tries to compensate for the fact that the user A’s rating of 2 is equal to Users B’s 4.

### Cosine

This is the strategy to pick if the data is sparse.

I.e. If there is a list with ten thousand artists, it quite likely that the users only listened and rated a few of them.

## Articles

There is a provided helper class for recommending articles that are similar to another article. The implementation is quite stupid, but it should give you a hint on how to expand this library with your own datasets.

### Usage

	$articleData = new \stojg\recommend\ArticleData();
	$allArticles = getFromDatabase();
	foreach($allArticles as $article) {
           $articleData->push($article->id, $article->content);
       }
      $recommendedArticle = $articleData->recommend($articleID = 4);
