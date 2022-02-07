<?php
namespace Cartrack\Tweets;

use Cartrack\Tweets\Exceptions\TweetLengthException;
use Cartrack\Tweets\Sentiments\Sentiment;

class Tweet
{
    private $id;

    private $userId;

    private $body;

    private $createAt;

    private $updatedAt;

    private $sentiments = [];

    public function __construct(
        ?int $id = null,
        int $userId,
        string $body,
        ?string $createAt = null,
        ?string $updatedAt = null
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->setBody($body);
        $this->createAt = $createAt;
        $this->updatedAt = $updatedAt;
    }

    protected function isValidLength(string $body): bool
    {
        return strlen($body) > 0 && strlen($body) < 255;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setUserId(int $userId)
    {
        $this->userId = $userId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body)
    {
        if($this->isValidLength($body) === false) {
            throw new TweetLengthException();
        }
        $this->body = $body;
    }

    public function addSentiment(Sentiment $sentiment)
    {
        $this->sentiments[] = $sentiment;
    }

    public function getSentiments(): array
    {
        return $this->sentiments;
    }

    public function getCreateAt(): string
    {
        return $this->createAt;
    }

    public function setCreateAt(string $createAt)
    {
        $this->createAt = $createAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(string $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
}
