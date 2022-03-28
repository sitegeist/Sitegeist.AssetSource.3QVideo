<?php

namespace Sitegeist\AssetSource\ThreeQVideo\Helper;

use Neos\Flow\Annotations as Flow;
use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Configuration\ConfigurationManager;
use Sitegeist\AssetSource\ThreeQVideo\ThreeQVideoClient;
use Sitegeist\AssetSource\ThreeQVideo\ValueObject\Playout;

class ThreeQHelper implements ProtectedContextAwareInterface
{
    /**
     * @Flow\Inject
     * @var ConfigurationManager
     */
    protected $configurationManager;

    public function playoutId(string $assetSource, $file): ?string
    {
        $assetSourceOptions = $this->configurationManager->getConfiguration(ConfigurationManager::CONFIGURATION_TYPE_SETTINGS, sprintf('Neos.Media.assetSources.%s.assetSourceOptions', $assetSource));
        $client = new ThreeQVideoClient($assetSourceOptions['apiKey'], $assetSourceOptions['projectId']);
        return $client->playouts($file)->first()->id;
    }

    public function allowsCallOfMethod($methodName)
    {
        return true;
    }

}
