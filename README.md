# Sitegeist.AssetSource.3QVideo

Asset Source for 3QVideo

## Setup

### Install composer package
`composer require sitegeist/assetsource-3qvideo`

### Configure asset source

Configure the asset source with the following properties, in your `Settings.yaml` - either global or in a site package

```
Neos:
  Media:
    assetSources:
      3qvideo:
        assetSource: 'Sitegeist\AssetSource\ThreeQVideo\AssetSource\ThreeQVideoAssetSource'
        assetSourceOptions:
          label: '3Q Video'
          description: ' 3Q Video asset source'
          apiKey: <api-key>
          projectId: <project-id>
```

## Acknowledgements
Inspired by the format of @bwaidelich  and the [Pimcore Asset source](https://github.com/bwaidelich/Wwwision.Neos.AssetSource.Pimcore/)
