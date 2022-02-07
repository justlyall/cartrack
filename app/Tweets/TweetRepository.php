<?php
namespace Cartrack\Tweets;

use Carbon\Carbon;
use Cartrack\Db\Database;
use Cartrack\Tweets\Exceptions\TweetNotFoundException;
use Cartrack\Tweets\Sentiments\Sentiment;
use Cartrack\Db\Database as Db;

class TweetRepository
{
    public function firstOrFail(int $id): Tweet
    {
        // ORM Sentiment::firstOrFail($id)
        $statement = Db::query("SELECT * FROM tweets WHERE id = :id", ['id' => $id]);
        $data = $statement->fetch(\PDO::FETCH_NUM);

        if (! $data) {
            throw new TweetNotFoundException();
        }

        return new Tweet(...$data);
    }

    public function associateSentiment(Tweet $tweet, Sentiment $sentiment)
    {
        // ORM $tweet->associate($sentiment)
        Db::query( "INSERT into tweets_sentiments(tweet_id, sentiment_id) VALUES(:tweet_id, :sentiment_id)", [
            'tweet_id' => $tweet->getId(),
            'sentiment_id' => $sentiment->getId()
        ]);
    }

    public function update(Tweet $tweet)
    {
        // ORM $tweet->save()
        $now = Carbon::now()->toDateTimeString();

        $statement = Db::query("UPDATE tweets set body = :body, updated_at = :updated_at WHERE id = :id", [
            'body' => $tweet->getBody(),
            'updated_at' => $now,
            'id' => $tweet->getId()
        ]);

        $tweet->setUpdatedAt($now);
    }

    public function delete(Tweet $tweet)
    {
        // ORM $tweet->delete()
        Db::query("DELETE from tweets WHERE id = :id", [
            'id' => $tweet->getId()
        ]);
    }

    public function deleteAssociatedSentiments(Tweet $tweet)
    {
        // ORM $tweet->sentimets->delete()
        Database::query("DELETE from tweets_sentiments WHERE tweet_id = :id", [
                'id' => $tweet->getId()
        ]);
    }

    public function create(Tweet $tweet)
    {
        // ORM $tweet->save();
        $now = Carbon::now()->toDateTimeString();

        $sql = "INSERT INTO tweets(body, user_id, created_at, updated_at) 
            VALUES (:body, :user_id, :created_at, :updated_at)";

        Db::query($sql, [
            'body' => $tweet->getBody(),
            'user_id' => $tweet->getUserId(),
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $tweet->setCreateAt($now);
        $tweet->setUpdatedAt($now);
        $tweet->setId(Db::$instance->lastInsertId());
    }

    public function findWithSentiments(int $tweetId): Tweet
    {
        //ORM Tweet::with('sentiments')->findOrFail($tweetId)
        $tweet = $this->firstOrFail($tweetId);
        $sql = "SELECT sentiments.* FROM tweets_sentiments, sentiments 
                WHERE tweet_id = :id 
                AND sentiments.id = sentiment_id";

        $statement = Db::query($sql, [
            'id' => $tweetId,
        ]);


        while (($data = $statement->fetch(\PDO::FETCH_NUM)) !== false) {
            $tweet->addSentiment(new Sentiment(...$data));
        }

        return $tweet;
    }

    public function search(string $searchTerm, string $since, int $limit = 15, int $offset = 0): array
    {
        # i know i'm doing an N + 1
        $sql = "SELECT id FROM tweets
                WHERE body ilike '%' || :search || '%'
                AND created_at > :since
                LIMIT :limit
                OFFSET :offset
                ";

        $statement = Db::query($sql, [
            'search' => $searchTerm ,
            'since' => $since,
            'limit' => $limit,
            'offset'=> $offset
        ]);

        $tweets = [];

        while (($data = $statement->fetch(\PDO::FETCH_ASSOC)) !== false) {
            $tweets[] = $this->findWithSentiments($data['id']);
        }

        return $tweets;
    }

    public function searchCount(string $searchTerm, string $since): int
    {
        $sql = "SELECT count(*) as total FROM tweets
                WHERE body ilike '%' || :search || '%'
                AND created_at > :since";

        $statement = Db::query($sql, [
            'search' => $searchTerm,
            'since' => $since
        ]);

        return (int) $statement->fetch(\PDO::FETCH_ASSOC)['total'];
    }
}