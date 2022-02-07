<?php

use Cartrack\Tweets\Exceptions\TweetNotFoundException;
use Cartrack\Tweets\Sentiments\Sentiment;
use Cartrack\Tweets\Tweet;
use Cartrack\Tweets\TweetRepository;

class TweetRepositoryTest extends DatabaseTestCase
{
    /**
     * @var TweetRepository
     */
    private $repository;

    public function setUp(): void
    {
        $this->repository = new TweetRepository();
        parent::setUp();
    }

    public function testFirstOrFail_TweetNotInDb_ThrowsException()
    {
        $this->expectException(TweetNotFoundException::class);
        $this->repository->firstOrFail(1);
    }

    public function testFirstOrFail_TweetInDb_ReturnsTweet()
    {
        $tweet = new Tweet(null, 1, 'I am a tweet');
        $this->repository->create($tweet);

        $actual = $this->repository->firstOrFail(1);
        $this->assertEquals($tweet->getBody(), $actual->getBody());
    }

    public function testCreate_SavesTweetInDb()
    {
        $tweet = new Tweet(null, 1, 'I am a tweet');
        $this->repository->create($tweet);
        $actual = $this->repository->firstOrFail(1);
        $this->assertEquals($actual, $tweet);
    }

    public function testDelete_RemovesTweetFromDb()
    {
        $this->expectException(TweetNotFoundException::class);
        $tweet = new Tweet(null, 1, 'I am a tweet');
        $this->repository->create($tweet);
        $this->repository->delete($tweet);
        $this->repository->firstOrFail(1);
    }

    public function testUpdate_SavesTweetInDb()
    {
        $this->repository->create(new Tweet(null, 1, 'I am a tweet'));

        $tweet = $this->repository->firstOrFail(1);
        $tweet->setBody('123');

        $this->repository->update($tweet);
        $actual = $this->repository->firstOrFail(1);

        $this->assertEquals($actual, $tweet);
    }

    public function testAssociateSentiment_SavesInDb()
    {
        $tweet = new Tweet(rand(1, 100), 1, 'test');
        $sentiment = new Sentiment(rand(1, 100), 'test');

        $this->repository->associateSentiment($tweet, $sentiment);

        $actual = $this->db->query("SELECT * FROM tweets_sentiments")->fetchAll();

        $this->assertCount(1, $actual);
        $this->assertEquals($tweet->getId(), $actual[0]['tweet_id']);
        $this->assertEquals($sentiment->getId(), $actual[0]['sentiment_id']);
    }

}