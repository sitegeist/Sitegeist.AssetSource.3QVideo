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
        return ThreeQVideoAssetProxy::fromFile($file, $this->assetSource);
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
        $query = new ThreeQVideoAssetProxyQuery($this->assetSource);
        $query->setSearchTerm($searchTerm);
        return new ThreeQVideoAssetProxyQueryResult($query);
    }

    public function findByTag(Tag $tag): AssetProxyQueryResultInterface
    {
        throw new \BadMethodCallException('findByTag is not supported by this repository', 1648042293);
    }

    public function findUntagged(): AssetProxyQueryResultInterface
    {
        throw new \BadMethodCallException('findByTag is not supported by this repository', 1648042305);
    }

    public function countAll(): int
    {
        $query = new ThreeQVideoAssetProxyQuery($this->assetSource);
        return $query->count();
    }

}
