<?php

use Carbon\Carbon;
use Cartrack\Tweets\Sentiments\Classifiers\SentimentClassifiersInterface;
use Cartrack\Tweets\Sentiments\SentimentRepository;
use Cartrack\Tweets\Tweet;
use Cartrack\Tweets\TweetCreator;
use Cartrack\Tweets\TweetRepository;

class TweetCreatorTest extends DatabaseTestCase
{
    public function testCreateOrUpdate_NewTweet_SavesInDbCorrectly()
    {
        $now = Carbon::now()->toDateTimeString();
        $tweet = new Tweet(null, 1, 'body');
        $expectedTweetAfterSave = new Tweet(1, 1, 'body', $now, $now);

        $sentiments = ['south africa', 'bmw'];

        $sentimentClassifierMock = $this->createMock(SentimentClassifiersInterface::class);
        $sentimentClassifierMock->expects($this->once())
            ->method('get')
            ->with($expectedTweetAfterSave)
            ->willReturn($sentiments);

        $tweetCreator = new TweetCreator(
            new TweetRepository(),
            $sentimentClassifierMock,
            new SentimentRepository()
        );

        $tweetCreator->createOrUpdate($tweet);

        $actualTweet = (new TweetRepository())->firstOrFail(1);
        $this->assertEquals($tweet, $actualTweet);

        $actualSentiments = $this->db->query("SELECT * FROM sentiments")
            ->fetchAll(\PDO::FETCH_ASSOC);

        $expectedSentiments = [[
            'id' => 1,
            'sentiment' => $sentiments[0],
            'created_at' => $now
        ], [
            'id' => 2,
            'sentiment' => $sentiments[1],
            'created_at' => $now
        ]];

        $this->assertEquals($expectedSentiments, $actualSentiments);

        $actualTweetSentiments = $this->db->query("SELECT tweet_id, sentiment_id FROM tweets_sentiments")
            ->fetchAll(\PDO::FETCH_ASSOC);

        $expectedTweetSentiments = [[
            'tweet_id' => 1,
            'sentiment_id' => 1
        ], [
            'tweet_id' => 1,
            'sentiment_id' => 2
        ]];

        $this->assertEquals($expectedTweetSentiments, $actualTweetSentiments);
    }

    public function testCreateOrUpdate_TweetIsInDb_UpdatesTweetCorrectly()
    {
        $now = Carbon::now()->toDateTimeString();
        $tweet = new Tweet(null, 1, 'body');


        $expectedTweetAfterSave = new Tweet(1, 1, 'body', $now, $now);
        $sentiments = ['south africa', 'bmw'];

        $sentimentClassifierMock = $this->createMock(SentimentClassifiersInterface::class);
        $sentimentClassifierMock->expects($this->once())
            ->method('get')
            ->with($expectedTweetAfterSave)
            ->willReturn($sentiments);

        $tweetCreator = new TweetCreator(
            new TweetRepository(),
            $sentimentClassifierMock,
            new SentimentRepository()
        );

        $tweetCreator->createOrUpdate($tweet);

        $actualTweet = (new TweetRepository())->firstOrFail(1);
        $this->assertEquals($tweet, $actualTweet);

        $actualSentiments = $this->db->query("SELECT * FROM sentiments")
            ->fetchAll(\PDO::FETCH_ASSOC);

        $expectedSentiments = [[
            'id' => 1,
            'sentiment' => $sentiments[0],
            'created_at' => $now
        ], [
            'id' => 2,
            'sentiment' => $sentiments[1],
            'created_at' => $now
        ]];

        $this->assertEquals($expectedSentiments, $actualSentiments);

        $actualTweetSentiments = $this->db->query("SELECT tweet_id, sentiment_id FROM tweets_sentiments")
            ->fetchAll(\PDO::FETCH_ASSOC);

        $expectedTweetSentiments = [[
            'tweet_id' => 1,
            'sentiment_id' => 1
        ], [
            'tweet_id' => 1,
            'sentiment_id' => 2
        ]];

        $this->assertEquals($expectedTweetSentiments, $actualTweetSentiments);
    }
}