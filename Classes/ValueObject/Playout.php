<?php

namespace Sitegeist\AssetSource\ThreeQVideo\ValueObject;

use GuzzleHttp\Psr7\Uri;
use Neos\Flow\Annotations as Flow;
use Doctrine\Common\Collections\ArrayCollection;
use Psr\Http\Message\UriInterface;

/**
 * @Flow\Proxy(false)
 */
class Playout implements \JsonSerializable
{
    public int $id;
    public string $label;

    private function __construct(int $id, string $label)
    {
        $this->id = $id;
        $this->label = $label;
    }

    public static function fromApiResult(array $result): self
    {
        return new self(
            (int)$result['Id'],
            (string) $result['Label']
        );
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id
        ];
    }


}
