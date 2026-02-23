<?php

namespace Sitegeist\AssetSource\ThreeQVideo\AssetSource;

use Neos\Media\Domain\Model\AssetSource\AssetProxyQueryInterface;
use Neos\Media\Domain\Model\AssetSource\AssetProxyQueryResultInterface;
use Neos\Media\Domain\Model\AssetSource\AssetSourceConnectionExceptionInterface;
use Sitegeist\AssetSource\ThreeQVideo\ValueObject\File;
use Sitegeist\AssetSource\ThreeQVideo\ValueObject\FileList;

class ThreeQVideoAssetProxyQuery implements AssetProxyQueryInterface
{
    /**
     * @var ThreeQVideoAssetSource
     */
    protected $assetSource;

    /**
     * @var int
     */
    private $limit = 20;

    /**
     * @var int
     */
    private $offset = 0;

    /**
     * @var string
     */
    private $searchTerm = '';

    /**
     * @var array
     */
    private $orderings = [];

    /**
     * @var array<string, FileList>
     */
    private $fileList = [];

    public function __construct(ThreeQVideoAssetSource $assetSource)
    {
        $this->assetSource = $assetSource;
    }

    public function getAssetSource(): ThreeQVideoAssetSource
    {
        return $this->assetSource;
    }

    public function setOffset(int $offset): void
    {
        $this->offset = $offset;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setSearchTerm(string $searchTerm)
    {
        $this->searchTerm = $searchTerm;
    }

    public function getSearchTerm()
    {
        return $this->searchTerm;
    }

    public function getFileList(): FileList
    {
        $discriminator = $this->offset . "__" . $this->limit . '__' . $this->searchTerm;
        if (!array_key_exists($discriminator, $this->fileList)) {
            $files = $this->assetSource->getApiClient()->files($this->offset, $this->limit);
            if ($this->searchTerm !== '') {
                $files = FileList::fromArray(
                    array_filter(
                        $files->toArray(),
                        function(File $file) {
                            return stripos(strtolower($file->metadata['Title']), strtolower($this->searchTerm)) !== false;
                        }
                    )
                );
            }
            $this->fileList[$discriminator] = $files;
        }
        return $this->fileList[$discriminator];
    }

    public function execute(): AssetProxyQueryResultInterface
    {
        return new ThreeQVideoAssetProxyQueryResult($this);
    }

    public function count(): int
    {
        return $this->getFileList()->count();
    }

}
