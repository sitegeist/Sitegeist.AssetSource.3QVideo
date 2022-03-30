<?php

namespace Sitegeist\AssetSource\ThreeQVideo\ValueObject;

use GuzzleHttp\Psr7\Uri;
use Neos\Flow\Annotations as Flow;
use Doctrine\Common\Collections\ArrayCollection;
use Psr\Http\Message\UriInterface;

/**
 * @Flow\Proxy(false)
 */
class File implements \JsonSerializable
{
    public int $id;
    public string $filename;
    public int $filesize;
    public UriInterface $url;
    public ?UriInterface $thumbnailUrl;
    public ?UriInterface $previewUrl;
    public string $mimetype;
    public array $metadata;
    public \DateTimeImmutable $creationDate;
    public \DateTimeImmutable $modificationDate;

    private function __construct(int $id, string $filename, int $filesize, UriInterface $url, ?UriInterface $thumbnailUrl, ?UriInterface $previewUrl, string $mimetype, array $metadata, \DateTimeImmutable $creationDate, \DateTimeImmutable $modificationDate)
    {
        $this->id = $id;
        $this->filename = $filename;
        $this->filesize = $filesize;
        $this->url = $url;
        $this->thumbnailUrl = $thumbnailUrl;
        $this->previewUrl = $previewUrl;
        $this->mimetype = $mimetype;
        $this->metadata = $metadata;
        $this->creationDate = $creationDate;
        $this->modificationDate = $modificationDate;
    }

    public static function fromApiResult(array $result): self
    {
        $url = new Uri();
        $metadata = $result['Metadata'];
        return new self(
            (int)$result['Id'],
            $metadata['OriginalFileName'],
            (int) $result['Properties']['Size'],
            $url,
            new Uri($metadata['StandardFilePicture']['URI']),
            new Uri($metadata['StandardFilePicture']['URI']),
            'video/3q',
            $metadata,
            new \DateTimeImmutable((string)$result['CreatedAt']),
            new \DateTimeImmutable((string)$result['LastUpdateAt'])
        );
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id
        ];
    }


}
