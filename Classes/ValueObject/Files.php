<?php

namespace Sitegeist\AssetSource\ThreeQVideo\ValueObject;

use Neos\Flow\Annotations as Flow;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Flow\Proxy(false)
 */
class Files
{
    protected array $files = [];

    protected function __construct(array $files)
    {
        $this->files = $files;
    }

    public static function create(array $files): self
    {
        return new self($files);
    }

    public function getFiles(): ArrayCollection
    {
        return new ArrayCollection($this->files);
    }

    public function count(): int
    {
        return $this->getFiles()->count();
    }
}
