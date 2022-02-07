<?php

use Cartrack\Tweets\Sentiments\Sentiment;
use Cartrack\Tweets\Sentiments\SentimentRepository;


class SentimentRepositoryTest extends DatabaseTestCase
{
    public function testUpsert_InsertsIntoDb()
    {
        $repository = new SentimentRepository();
        $sentiment = $repository->upsert('sentiment');

        $actual = $this->db->query("SELECT * FROM sentiments")->fetchAll();

        $this->assertCount(1, $actual);
        $this->assertEquals($sentiment->getId(), $actual[0]['id']);
        $this->assertEquals($sentiment->getSentiment(), $actual[0]['sentiment']);
        $this->assertEquals($sentiment->getCreatedAt(), $actual[0]['created_at']);
    }
}