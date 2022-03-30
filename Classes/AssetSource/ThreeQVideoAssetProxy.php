<?php

namespace Sitegeist\AssetSource\ThreeQVideo\AssetSource;

use Neos\Flow\Annotations as Flow;
use Neos\Media\Domain\Model\AssetSource\AssetProxy\AssetProxyInterface;
use Neos\Media\Domain\Model\AssetSource\AssetProxy\HasRemoteOriginalInterface;
use Neos\Media\Domain\Model\AssetSource\AssetSourceInterface;
use Neos\Media\Domain\Model\ImportedAsset;
use Neos\Media\Domain\Repository\ImportedAssetRepository;
use Psr\Http\Message\UriInterface;
use Sitegeist\AssetSource\ThreeQVideo\ValueObject\File;
use Sitegeist\AssetSource\ThreeQVideo\ValueObject\Playout;
use Sitegeist\AssetSource\ThreeQVideo\ValueObject\Playouts;

final class ThreeQVideoAssetProxy implements AssetProxyInterface, HasRemoteOriginalInterface
{

    private ThreeQVideoAssetSource $assetSource;
    private File $file;
    private ?string $localAssetIdentifier = null;

    public function __construct(ThreeQVideoAssetSource $assetSource, File $file)
    {
        $this->assetSource = $assetSource;
        $this->file = $file;
    }


    public function getAssetSource(): AssetSourceInterface
    {
        return $this->assetSource;
    }

    public function getIdentifier(): string
    {
        return (string) $this->file->id;
    }

    public function getLabel(): string
    {
        return $this->file->filename;
    }

    public function getFilename(): string
    {
        return $this->file->filename;
    }

    public function getLastModified(): \DateTimeInterface
    {
        return $this->file->modificationDate;
    }

    public function getFileSize(): int
    {
        return $this->file->filesize;
    }

    public function getMediaType(): string
    {
        return $this->file->mimetype;
    }

    public function getWidthInPixels(): ?int
    {
        return null;
    }

    public function getHeightInPixels(): ?int
    {
        return null;
    }

    public function getThumbnailUri(): ?UriInterface
    {
        return $this->file->thumbnailUrl;
    }

    public function getPreviewUri(): ?UriInterface
    {
        return $this->file->previewUrl;
    }

    public function getImportStream()
    {
        $handle = fopen('php://memory', 'r+');
        fwrite($handle, json_encode([
            'id' => $this->file->id,
            'playoutId' => $this->getPlayouts()->first()->id
        ]));
        rewind($handle);
        return $handle;
    }

    public function getPlayouts(): Playouts
    {
        return $this->assetSource->getApiClient()->playouts($this->file->id);
    }

    public function getLocalAssetIdentifier(): ?string
    {
        if ($this->localAssetIdentifier === null) {
            $this->localAssetIdentifier = $this->assetSource->getLocalAssetIdentifier($this->getIdentifier());
        }
        return $this->localAssetIdentifier;
    }

    public function isImported(): bool
    {
        $importedAsset = (new ImportedAssetRepository())->findOneByAssetSourceIdentifierAndRemoteAssetIdentifier($this->assetSource->getIdentifier(), $this->getIdentifier());
        return ($importedAsset instanceof ImportedAsset);
    }


}
