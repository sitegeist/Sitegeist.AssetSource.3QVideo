<?php

namespace Sitegeist\AssetSource\ThreeQVideo\Helper;

use Neos\Eel\ProtectedContextAwareInterface;

class AssetHelper implements ProtectedContextAwareInterface
{
    /**
     * Read and return the resource contents for further use.
     *
     * @param resource $resource
     * @return ?string
     */
    public function readResource($resource): ?string
    {
        if (is_resource($resource) === false) {
            throw new \InvalidArgumentException(sprintf('Given argument was not a valid `resource` object. "%s" given', gettype($resource)));
        }
        return stream_get_contents($resource);
    }

    public function allowsCallOfMethod($methodName)
    {
        return true;
    }

}
