<?php
namespace Cartrack\Tweets\Sentiments;

class Sentiment
{
    private $id;

    private $sentiment;

    private $createdAt;

    public function __construct(?int $id = null, string $sentiment, ?string $createdAt = null)
    {
        $this->id = $id;
        $this->sentiment = $sentiment;
        $this->createdAt = $createdAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getSentiment(): string
    {
        return $this->sentiment;
    }

    public function setSentiment(string $sentiment)
    {
        $this->sentiment = $sentiment;
    }

    public function setCreatedAt(string $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
}