<?php

namespace Sitegeist\AssetSource\ThreeQVideo\Domain;

use Neos\Flow\Annotations as Flow;
use Neos\Media\Domain\Model\Adjustment\QualityImageAdjustment;
use Neos\Media\Domain\Model\Adjustment\ResizeImageAdjustment;
use Neos\Media\Domain\Model\Thumbnail;
use Neos\Media\Domain\Model\ThumbnailGenerator\AbstractThumbnailGenerator;
use Neos\Media\Domain\Model\Video;
use Neos\Media\Domain\Service\ImageService;
use Sitegeist\AssetSource\ThreeQVideo\AssetSource\ThreeQVideoAssetProxy;
use Neos\Media\Exception\NoThumbnailAvailableException;

class ThumbnailGenerator extends AbstractThumbnailGenerator
{

    protected static $priority = 10;

    /**
     * @var ImageService
     * @Flow\Inject
     */
    protected $imageService;

    public function canRefresh(Thumbnail $thumbnail)
    {
        return ($thumbnail->getOriginalAsset()->getAssetProxy()->getMediaType() === 'video/3q');
    }

    public function refresh(Thumbnail $thumbnail)
    {
        $resource = $this->resourceManager->importResource($thumbnail->getOriginalAsset()->getAssetProxy()->getThumbnailUri());
        try {
            $adjustments = [
                new ResizeImageAdjustment(
                    [
                        'width' => $thumbnail->getConfigurationValue('width'),
                        'maximumWidth' => $thumbnail->getConfigurationValue('maximumWidth'),
                        'height' => $thumbnail->getConfigurationValue('height'),
                        'maximumHeight' => $thumbnail->getConfigurationValue('maximumHeight'),
                        'ratioMode' => $thumbnail->getConfigurationValue('ratioMode'),
                        'allowUpScaling' => $thumbnail->getConfigurationValue('allowUpScaling'),
                    ]
                ),
                new QualityImageAdjustment(
                    [
                        'quality' => $thumbnail->getConfigurationValue('quality')
                    ]
                )
            ];

            $targetFormat = $thumbnail->getConfigurationValue('format');
            $processedImageInfo = $this->imageService->processImage($resource, $adjustments, $targetFormat);

            $thumbnail->setResource($processedImageInfo['resource']);
            $thumbnail->setWidth($processedImageInfo['width']);
            $thumbnail->setHeight($processedImageInfo['height']);
            $thumbnail->setQuality($processedImageInfo['quality']);
        } catch (\Exception $exception) {
            $message = sprintf('Unable to generate thumbnail for the 3Q preview uri (uri: %s, SHA1: %s)', $thumbnail->getOriginalAsset()->getAssetProxy()->getThumbnailUri(), $resource->getSha1());
            throw new NoThumbnailAvailableException($message, 1648636368, $exception);
        }
    }


}
