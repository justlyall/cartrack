<?php
namespace Cartrack\Tweets;

class TweetDeleter
{
    private $tweetRepository;

    public function __construct(TweetRepository $tweetRepository)
    {
        $this->tweetRepository = $tweetRepository;
    }

    public function delete(Tweet $tweet)
    {
        $this->tweetRepository->deleteAssociatedSentiments($tweet);
        $this->tweetRepository->delete($tweet);
    }
}