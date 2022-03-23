<?php

namespace Sitegeist\AssetSource\ThreeQVideo\AssetSource;

use Neos\Media\Domain\Model\AssetSource\AssetNotFoundExceptionInterface;
use Neos\Media\Domain\Model\AssetSource\AssetProxy\AssetProxyInterface;
use Neos\Media\Domain\Model\AssetSource\AssetProxyQueryResultInterface;
use Neos\Media\Domain\Model\AssetSource\AssetProxyRepositoryInterface;
use Neos\Media\Domain\Model\AssetSource\AssetSourceConnectionExceptionInterface;
use Neos\Media\Domain\Model\AssetSource\AssetTypeFilter;
use Neos\Media\Domain\Model\AssetSource\Neos\NeosAssetProxyQuery;
use Neos\Media\Domain\Model\Tag;

class ThreeQVideoAssetProxyRepository implements AssetProxyRepositoryInterface
{

    protected ThreeQVideoAssetSource $assetSource;

    public function __construct(ThreeQVideoAssetSource $assetSource)
    {
        $this->assetSource = $assetSource;
    }


    public function getAssetProxy(string $identifier): AssetProxyInterface
    {
        $file = $this->assetSource->getApiClient()->file((int) $identifier);
        return new ThreeQVideoAssetProxy($this->assetSource, $file);
    }

    public function filterByType(AssetTypeFilter $assetType = null): void
    {

    }

    public function findAll(): AssetProxyQueryResultInterface
    {
        $query = new ThreeQVideoAssetProxyQuery($this->assetSource);
        return $query->execute();
    }

    public function findBySearchTerm(string $searchTerm): AssetProxyQueryResultInterface
    {
        // TODO: Implement findBySearchTerm() method.
    }

    public function findByTag(Tag $tag): AssetProxyQueryResultInterface
    {
        // TODO: Implement findByTag() method.
    }

    public function findUntagged(): AssetProxyQueryResultInterface
    {
        // TODO: Implement findUntagged() method.
    }

    public function countAll(): int
    {
        // TODO: Implement countAll() method.
    }

}
