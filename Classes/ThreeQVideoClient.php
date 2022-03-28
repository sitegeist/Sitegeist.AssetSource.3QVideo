<?php

namespace Sitegeist\AssetSource\ThreeQVideo;

use GuzzleHttp\Psr7\Utils;
use Neos\Flow\Annotations as Flow;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;
use Sitegeist\AssetSource\ThreeQVideo\ValueObject\File;
use Sitegeist\AssetSource\ThreeQVideo\ValueObject\FileList;
use Sitegeist\AssetSource\ThreeQVideo\ValueObject\Playouts;

class ThreeQVideoClient
{

    private string $apiKey;
    private string $projectId;

    protected ?Client $client = null;

    /**
     * @var LoggerInterface
     * @Flow\Inject
     */
    protected $logger;

    public function __construct(string $apiKey, string $projectId)
    {
        $this->apiKey = $apiKey;
        $this->projectId = $projectId;
    }

    public function file(int $id): ?File
    {
        try {
            $response = $this->getClient()->get(
                sprintf('https://sdn.3qsdn.com/api/v2/projects/%s/files/%s', $this->projectId, $id)
            );
            $file = \GuzzleHttp\Utils::jsonDecode($response->getBody()->getContents(), true);

            return File::fromApiResult($file);
        } catch (GuzzleException $exception) {
            $this->logger->error(
                sprintf('Error querying file %s for 3Q project %s', $id, $this->projectId),
                [$exception->getMessage()]
            );
            return null;
        }
    }

    public function playouts(int $file): ?Playouts
    {
        try {
            $response = $this->getClient()->get(
                sprintf('https://sdn.3qsdn.com/api/v2/projects/%s/files/%s/playouts', $this->projectId, $file->id)
            );
            $file = \GuzzleHttp\Utils::jsonDecode($response->getBody()->getContents(), true);

            return Playouts::fromApiResult($file);
        } catch (GuzzleException $exception) {
            $this->logger->error(
                sprintf('Error querying playouts for file %s for 3Q project %s', $file->id, $this->projectId),
                [$exception->getMessage()]
            );
            return null;
        }
    }

    public function files(): FileList
    {
        try {
            $response = $this->getClient()->get(
                sprintf('https://sdn.3qsdn.com/api/v2/projects/%s/files?IncludeMetadata=true&IncludeProperties=true', $this->projectId)
            );
            $result = \GuzzleHttp\Utils::jsonDecode($response->getBody()->getContents(), true);

            return FileList::fromApiResult($result);
        } catch (GuzzleException $exception) {
            $this->logger->error(
                sprintf('Error querying files for 3Q project %s', $this->projectId),
                [$exception->getMessage()]
            );
            return FileList::empty();
        }

    }

    private function getClient(): Client
    {
        if ($this->client === null) {
            $this->client = new Client([
                'headers' => [
                    'User-Agent' => 'Neos Asset Source Client',
                    'X-AUTH-APIKEY' => $this->apiKey
                ]
            ]);
        }

        return $this->client;
    }
}
