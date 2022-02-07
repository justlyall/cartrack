<?php
namespace Cartrack\Tweets\Sentiments\Classifiers;

use Cartrack\Tweets\Tweet;
use Google\Cloud\Language\V1\Document;
use Google\Cloud\Language\V1\LanguageServiceClient;
use Google\Cloud\Language\V1beta2\Document\Type;
use Google\Protobuf\Internal\RepeatedField;

class GoogleSentimentClassifier implements SentimentClassifiersInterface
{
    private $languageServiceClient;

    public function __construct(LanguageServiceClient $languageServiceClient)
    {
        $this->languageServiceClient = $languageServiceClient;
    }

    public function get(Tweet $tweet): array
    {
        $document = (new Document())
            ->setContent($tweet->getBody())
            ->setType(Type::PLAIN_TEXT);

        try {
            return $this->transformToArray(
                $this->languageServiceClient->analyzeEntities($document)->getEntities()
            );
        } catch (\Exception $exception) {
            return [];
        }
    }

    private function transformToArray(RepeatedField $entities): array
    {
        $entitiesArray = [];
        foreach ($entities as $entities) {
            $entitiesArray[] = $entities->getName();
        }
        return $entitiesArray;
    }
}