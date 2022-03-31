<?php

namespace Sitegeist\AssetSource\ThreeQVideo\AssetSource;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\ResourceManagement\ResourceManager;
use Neos\Media\Domain\Model\AssetSource\AssetProxyRepositoryInterface;
use Neos\Media\Domain\Model\AssetSource\AssetSourceInterface;
use Neos\Media\Domain\Model\ImportedAsset;
use Neos\Media\Domain\Repository\ImportedAssetRepository;
use Sitegeist\AssetSource\ThreeQVideo\ThreeQVideoClient;

class ThreeQVideoAssetSource implements AssetSourceInterface
{
    /**
     * @Flow\Inject
     * @var ResourceManager
     */
    protected $resourceManager;

    /**
     * @var string
     */
    private $identifier;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var AssetProxyRepositoryInterface
     */
    protected $assetProxyRepository;

    /**
     * @var ?ThreeQVideoClient
     */
    protected $apiClient = null;

    public function __construct(string $identifier, array $options)
    {
        $this->identifier = $identifier;
        $this->label = $options['label'] ?? '3QVideo';
        $this->description = $options['description'] ?? '';
        $this->options = $options;
    }

    public static function createFromConfiguration(string $assetSourceIdentifier, array $assetSourceOptions): AssetSourceInterface
    {
        return new static($assetSourceIdentifier, $assetSourceOptions);
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function isReadOnly(): bool
    {
        return true;
    }

    public function getIconUri(): string
    {
        return $this->resourceManager->getPublicPackageResourceUriByPath('resource://Sitegeist.AssetSource.3QVideo/Public/Icons/asset-source-icon.png');
    }

    public function getAssetProxyRepository(): AssetProxyRepositoryInterface
    {
        if ($this->assetProxyRepository === null) {
            $this->assetProxyRepository = new ThreeQVideoAssetProxyRepository($this);
        }

        return $this->assetProxyRepository;
    }

    public function getLocalAssetIdentifier(string $remoteAssetIdentifier): ?string
    {
        $importedAsset = (new ImportedAssetRepository())->findOneByAssetSourceIdentifierAndRemoteAssetIdentifier($this->getIdentifier(), $remoteAssetIdentifier);
        return $importedAsset instanceof ImportedAsset ? $importedAsset->getLocalAssetIdentifier() : null;
    }

    public function getApiClient(): ?ThreeQVideoClient
    {
        if ($this->apiClient === null) {
            $this->apiClient = new ThreeQVideoClient(
                $this->options['apiKey'],
                $this->options['projectId']
            );
        }

        return $this->apiClient;
    }
}
