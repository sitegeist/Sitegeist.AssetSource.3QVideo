<?php

namespace Sitegeist\AssetSource\ThreeQVideo\ValueObject;

use Neos\Flow\Annotations as Flow;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Flow\Proxy(false)
 */
class Playouts
{
    protected array $playouts = [];

    protected function __construct(array $playouts)
    {
        $this->playouts = $playouts;
    }

    public static function fromApiResult(array $result): self
    {
        $playouts = array_map(static fn(array $file) => Playout::fromApiResult($file), $result['FilePlayouts']);
        return new self(array_values($playouts));
    }

    public function first(): ?Playout
    {
        return $this->getPlayouts()->first();
    }

    public function getPlayouts(): ArrayCollection
    {
        return new ArrayCollection($this->playouts);
    }

    public function count(): int
    {
        return $this->getPlayouts()->count();
    }
}
