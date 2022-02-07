<?php
use Phinx\Migration\AbstractMigration;

class CreateTweetsSentimentsTable extends AbstractMigration
{
    public function change()
    {
        $this->table('tweets_sentiments')
            ->addColumn('tweet_id', 'integer')
            ->addColumn('sentiment_id', 'integer')
            ->addIndex(['tweet_id', 'sentiment_id'])
            ->create();
    }
}
