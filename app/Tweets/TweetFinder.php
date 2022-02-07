<?php
namespace Cartrack\Tweets;

use Carbon\Carbon;
use Psr\Http\Message\ServerRequestInterface;

class TweetFinder
{
    private $tweetRepository;

    public function __construct(TweetRepository $tweetRepository)
    {
        $this->tweetRepository = $tweetRepository;
    }

    public function find(ServerRequestInterface $serverRequest): array
    {
        $queryParams = $serverRequest->getQueryParams();
        $page = $queryParams['page'] ?? 1;
        $limit = $queryParams['limit'] ?? 15;
        $query = $queryParams['query'] ?? '';
        $since = $queryParams['since'] ?? 0;
        $since = Carbon::parse($since)->toDateTimeString();

        $total = $this->tweetRepository->searchCount($query, $since);
        if ($total !== 0) {
            $offset = (ceil($total / $limit) - 1) * $limit;
        } else {
            $offset = 0;
        }

        $tweets = $this->tweetRepository->search($query, $since, $limit, $offset);

        return [
            "search_metadata" => [
                "query" => $query,
                "count" => $total,
                "page" => $page,
                "limit" => $limit
            ],
            "tweets" => $tweets
        ];
    }
}