<?php

use Cartrack\Tweets\Tweet;
use Cartrack\Tweets\TweetDeleter;
use Cartrack\Tweets\TweetRepository;
use PHPUnit\Framework\TestCase;

class TweetDeleterTest extends TestCase
{
    public function testDelete()
    {
        $tweet = new Tweet(1, 1, 'abd');
        $tweetRepository = $this->createMock(TweetRepository::class);
        $tweetRepository->expects($this->once())
            ->method('deleteAssociatedSentiments')
            ->with($tweet);

        $tweetRepository->expects($this->once())
            ->method('delete')
            ->with($tweet);

        $deleter = new TweetDeleter($tweetRepository);
        $deleter->delete($tweet);
    }
}