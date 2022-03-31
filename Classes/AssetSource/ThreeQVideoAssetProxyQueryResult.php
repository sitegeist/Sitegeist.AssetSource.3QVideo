<?php

namespace Sitegeist\AssetSource\ThreeQVideo\AssetSource;

use Neos\Media\Domain\Model\AssetSource\AssetProxy\AssetProxyInterface;
use Neos\Media\Domain\Model\AssetSource\AssetProxyQueryInterface;
use Neos\Media\Domain\Model\AssetSource\AssetProxyQueryResultInterface;
use Sitegeist\AssetSource\ThreeQVideo\ValueObject\File;
use Sitegeist\AssetSource\ThreeQVideo\ValueObject\FileList;

class ThreeQVideoAssetProxyQueryResult implements AssetProxyQueryResultInterface
{

    /**
     * @var ThreeQVideoAssetProxyQuery
     */
    protected $query;

    /**
     * @var FileList|null
     */
    protected $fileListRuntimeCache = null;

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
        return $firstFile !== false ? ThreeQVideoAssetProxy::fromFile($firstFile, $this->query->getAssetSource()) : null;
    }

    public function toArray(): array
    {
        return array_map(
            function(File $file) {
                return ThreeQVideoAssetProxy::fromFile($file, $this->query->getAssetSource());
                },
            $this->getFiles()->toArray()
        );
    }

    public function current(): ?ThreeQVideoAssetProxy
    {
        $files = $this->getFiles();
        $file = $files->current();
        if ($file === null) {
            return null;
        }
        return ThreeQVideoAssetProxy::fromFile($file, $this->query->getAssetSource());
    }

    public function next(): void
    {
        $this->getFiles()->next();
    }

    public function key()
    {
        return $this->getFiles()->key();
    }

    public function valid()
    {
        return $this->getFiles()->valid();
    }

    public function rewind(): void
    {
        $this->getFiles()->rewind();
    }

    public function offsetExists($offset)
    {
        return $this->getFiles()->offsetExists($offset);
    }

    public function offsetGet($offset)
    {
        $file = $this->getFiles()->offsetGet($offset);
        return $file !== null ? ThreeQVideoAssetProxy::fromFile($file, $this->query->getAssetSource()) : null;
    }

    public function offsetSet($offset, $value)
    {
        $this->getFiles()->offsetSet($offset, $value);
    }

    public function offsetUnset($offset)
    {
        $this->getFiles()->offsetUnset($offset);
    }

    public function count(): int
    {
        return $this->getFiles()->count();
    }

}
