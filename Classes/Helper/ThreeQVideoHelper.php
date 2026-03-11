<?php

namespace Sitegeist\AssetSource\ThreeQVideo\Helper;

use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Media\Domain\Model\AssetInterface;
use Sitegeist\AssetSource\ThreeQVideo\AssetSource\ThreeQVideoAssetProxy;

class ThreeQVideoHelper implements ProtectedContextAwareInterface
{
    /**
     * Read and return the resource the playout id
     *
     * @param AssetInterface $asset
     * @return ?string
     */
    public function playoutIdForAsset(AssetInterface $asset): ?string
    {
        $proxy = $asset->getAssetProxy();
        if (!$proxy instanceof ThreeQVideoAssetProxy || $proxy->getMediaType() !== 'video/3q') {
            throw new \InvalidArgumentException(sprintf('A video/3q asset is required %s given', $asset->getAssetProxy()->getMediaType()));
        }
        $content = stream_get_contents($asset->getResource()->getStream());
        $data = json_decode($content, true);
        return $data['playoutId'] ?? null;
    }

    /**
     * Read and return the resource the playout id
     *
     * @param AssetInterface $asset
     * @return ?string
     */
    public function placeholderForAsset(AssetInterface $asset): ?string
    {
        $proxy = $asset->getAssetProxy();
        if (!$proxy instanceof ThreeQVideoAssetProxy || $proxy->getMediaType() !== 'video/3q') {
            throw new \InvalidArgumentException(sprintf('A video/3q asset is required %s given', $asset->getAssetProxy()->getMediaType()));
        }
        $uri = $asset->getAssetProxy()->getPreviewUri();
        return $uri ? (string) $uri : null ;
    }

    public function allowsCallOfMethod($methodName)
    {
        return true;
    }

}
