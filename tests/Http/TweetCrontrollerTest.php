<?php

use Carbon\Carbon;
use Cartrack\Http\Responses\ErrorResponse;
use Cartrack\Http\TweetController;
use Cartrack\Tweets\Exceptions\TweetLengthException;
use Cartrack\Tweets\Tweet;
use Cartrack\Tweets\TweetCreator;
use Cartrack\Tweets\TweetDeleter;
use Cartrack\Tweets\TweetRepository;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;

class TweetCrontrollerTest extends TestCase
{
    private $tweetController;

    public function setUp(): void
    {
        $this->tweetController = new TweetController();
        parent::setUp();
    }

    public function testCreate_CreatesTweet()
    {
        $body = $this->createMock(StreamInterface::class);
        $body->expects($this->once())
            ->method('getContents')
            ->willReturn(json_encode(['body' => '123']));

        $request = $this->createMock(ServerRequest::class);
        $request->expects($this->once())
            ->method('getBody')
            ->willReturn($body);

        $tweetCreator = $this->createMock(TweetCreator::class);
        $tweetCreator->expects($this->once())
            ->method('createOrUpdate')
            ->with(new Tweet(null, 1, '123'));

        $this->tweetController->create($request, $tweetCreator);
    }

    public function testCreate_tweetToLong_ReturnsErrorResponse()
    {
        $body = $this->createMock(StreamInterface::class);
        $body->expects($this->once())
            ->method('getContents')
            ->willReturn(json_encode(['body' => '123']));

        $request = $this->createMock(ServerRequest::class);
        $request->expects($this->once())
            ->method('getBody')
            ->willReturn($body);

        $tweetCreator = $this->createMock(TweetCreator::class);
        $tweetCreator->expects($this->once())
            ->method('createOrUpdate')
            ->willThrowException(new TweetLengthException());

        $response = $this->tweetController->create($request, $tweetCreator);
        $this->assertEquals(ErrorResponse::class, get_class($response));
    }

    public function testUpdate_UpdatesTweet()
    {
        $body = $this->createMock(StreamInterface::class);
        $body->expects($this->once())
            ->method('getContents')
            ->willReturn(json_encode(['body' => '123']));

        $request = $this->createMock(ServerRequest::class);
        $request->expects($this->once())
            ->method('getBody')
            ->willReturn($body);

        $tweet = new Tweet(null, 1 , '123');

        $tweetRepository = $this->createMock(TweetRepository::class);
        $tweetRepository->expects($this->once())
            ->method('firstOrFail')
            ->willReturn($tweet);

        $tweetCreator = $this->createMock(TweetCreator::class);
        $tweetCreator->expects($this->once())
            ->method('createOrUpdate')
            ->with($tweet);

        $this->tweetController->update($request, $tweetCreator, $tweetRepository);
    }

    public function testDelete_DeleteTweet()
    {
        $request = $this->createMock(ServerRequest::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->willReturn(1);

        $tweet = new Tweet(1, 1 , '123');

        $tweetRepository = $this->createMock(TweetRepository::class);
        $tweetRepository->expects($this->once())
            ->method('firstOrFail')
            ->willReturn($tweet);

        $tweetDeleter = $this->createMock(TweetDeleter::class);
        $tweetDeleter->expects($this->once())
            ->method('delete')
            ->with($tweet);

        $this->tweetController->delete($request, $tweetDeleter, $tweetRepository);
    }

    public function testView_ReturnsTweet()
    {
        $request = $this->createMock(ServerRequest::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->willReturn(1);

        $now = Carbon::now()->toDateTimeString();
        $tweet = new Tweet(1, 1 , '123', $now, $now);

        $tweetRepository = $this->createMock(TweetRepository::class);
        $tweetRepository->expects($this->once())
            ->method('findWithSentiments')
            ->willReturn($tweet);

        $this->tweetController->view($request, $tweetRepository);
    }
}
