<?php
namespace Cartrack\Tweets;

use Cartrack\Tweets\Sentiments\Classifiers\SentimentClassifiersInterface;
use Cartrack\Tweets\Sentiments\SentimentRepository;

class TweetCreator
{
    private $tweetRepository;

    private $tweetSentimentClassifier;

    private $sentimentRepository;

    public function __construct(
        TweetRepository $tweetRepository,
        SentimentClassifiersInterface $tweetSentimentClassifier,
        SentimentRepository $sentimentRepository
    ) {
        $this->tweetRepository = $tweetRepository;
        $this->tweetSentimentClassifier = $tweetSentimentClassifier;
        $this->sentimentRepository = $sentimentRepository;
    }

    public function createOrUpdate(Tweet $tweet): Tweet
    {
        if (is_null($tweet->getId())) {
            $this->tweetRepository->create($tweet);
        } else {
            $this->tweetRepository->deleteAssociatedSentiments($tweet);
            $this->tweetRepository->update($tweet);
        }

        $nlpSentiments = $this->tweetSentimentClassifier->get($tweet);
        $this->createOrUpdateSentiments($nlpSentiments, $tweet);

        return $this->tweetRepository->findWithSentiments($tweet->getId());
    }
    
    private function createOrUpdateSentiments(array $nlpSentiments, Tweet $tweet)
    {
        foreach ($nlpSentiments as $nplSentiment) {
            $sentiment = $this->sentimentRepository->upsert($nplSentiment);
            $this->tweetRepository->associateSentiment($tweet, $sentiment);
        }
    }
}