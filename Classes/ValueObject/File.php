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
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $filename;

    /**
     * @var int
     */
    public $filesize;

    /**
     * @var UriInterface
     */
    public $url;

    /**
     * @var UriInterface|null
     */
    public $thumbnailUrl;

    /**
     * @var UriInterface|null
     */
    public $previewUrl;

    /**
     * @var string
     */
    public $mimetype;

    /**
     * @var array
     */
    public $metadata;

    /**
     * @var \DateTimeImmutable
     */
    public $creationDate;

    /**
     * @var \DateTimeImmutable
     */
    public $modificationDate;

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
            $metadata['StandardFilePicture'] ? new Uri($metadata['StandardFilePicture']['URI']) : null,
            $metadata['StandardFilePicture'] ? new Uri($metadata['StandardFilePicture']['URI']) : null,
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
