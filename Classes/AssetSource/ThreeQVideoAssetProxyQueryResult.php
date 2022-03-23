<?php

namespace Sitegeist\AssetSource\ThreeQVideo\AssetSource;

use Neos\Media\Domain\Model\AssetSource\AssetProxy\AssetProxyInterface;
use Neos\Media\Domain\Model\AssetSource\AssetProxyQueryInterface;
use Neos\Media\Domain\Model\AssetSource\AssetProxyQueryResultInterface;
use Sitegeist\AssetSource\ThreeQVideo\ValueObject\File;
use Sitegeist\AssetSource\ThreeQVideo\ValueObject\FileList;

class ThreeQVideoAssetProxyQueryResult implements AssetProxyQueryResultInterface
{

    protected ThreeQVideoAssetProxyQuery $query;
    protected ?FileList $fileListRuntimeCache = null;

    public function __construct(ThreeQVideoAssetProxyQuery $query)
    {
        $this->query = $query;
    }

    private function getFiles(): FileList
    {
        if ($this->fileListRuntimeCache === null) {
            $this->fileListRuntimeCache = $this->query->getFileList();
        }

        return $this->fileListRuntimeCache;
    }

    public function getQuery(): AssetProxyQueryInterface
    {
        return clone $this->query;
    }

    public function getFirst(): ?AssetProxyInterface
    {
        $files = $this->getFiles()->toArray();
        $firstFile =  reset($files);
        return $firstFile !== false ? new ThreeQVideoAssetProxy($this->query->getAssetSource(), $firstFile) : null;
    }

    public function toArray(): array
    {
        return array_map(fn(File $file) => new ThreeQVideoAssetProxy($this->query->getAssetSource(), $file), $this->getFiles()->toArray());
    }

    public function current(): mixed
    {
        $files = $this->getFiles();
        $file = $files->current();
        if ($file === null) {
            return null;
        }
        return new ThreeQVideoAssetProxy($this->query->getAssetSource(), $file);
    }

    public function next(): void
    {
        $this->getFiles()->next();
    }

    public function key(): mixed
    {
        return $this->getFiles()->key();
    }

    public function valid(): bool
    {
        return $this->getFiles()->valid();
    }

    public function rewind(): void
    {
        $this->getFiles()->rewind();
    }

    public function offsetExists(mixed $offset): bool
    {
        return $this->getFiles()->offsetExists($offset);
    }

    public function offsetGet(mixed $offset): ?ThreeQVideoAssetProxy
    {
        $file = $this->getFiles()->offsetGet($offset);
        return $file !== null ? new ThreeQVideoAssetProxy($this->query->getAssetSource(), $file) : null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->getFiles()->offsetSet($offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->getFiles()->offsetUnset($offset);
    }

    public function count(): int
    {
        return $this->getFiles()->count();
    }

}
