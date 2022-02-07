<?php
namespace Cartrack\Tweets\Sentiments;

use Carbon\Carbon;
use Cartrack\Db\Database;

class SentimentRepository
{
    public function upsert(string $sentimentName): Sentiment
    {
        $sentiment = $this->findBySentiment($sentimentName);

        return (is_null($sentiment))
            ? $this->insert($sentimentName)
            : $sentiment;
    }

    public function insert(string $sentiment)
    {
        $now = Carbon::now()->toDateTimeString();

        $sql = "insert into sentiments(sentiment, created_at) 
                    VALUES (:sentiment, :created_at) ON CONFLICT DO NOTHING ";

        Database::query($sql, [
            'sentiment' => $sentiment,
            'created_at' => $now
        ]);

        return new Sentiment(
            Database::getInstance()->lastInsertId(),
            $sentiment,
            $now
        );
    }

    public function findBySentiment(string $sentiment): ?Sentiment
    {
        $sql = "SELECT * from sentiments WHERE sentiment = :sentiment ";
        $statement = Database::query($sql, ['sentiment' => $sentiment]);
        $data = $statement->fetch(\PDO::FETCH_NUM);
        return $data === false ? null : new Sentiment(...$data);
    }
}
