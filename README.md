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

### Use the assets in your own project

The package contains a Mixin for selecting 3Q Videos and a presentational component for rendering the videoplayer. 
To actually use the building blocks in your own project you have to define a NodeType with configuration and rendering.

NodeTypes.3QVideo.yaml:
```
'Vendor.Site:Content.3QVideo':
  ui:
    label: '3Q Video'
    icon: video
  superTypes:
    'Neos.Neos:Content'    
    'Sitegeist.AssetSource.3QVideo:3QVideo': true
```

Content.3QVideo.fusion:
```
prototype(Vendor.Site:Content.3QVideo) < prototype(Neos.Neos:ContentComponent) {
    asset = ${q(node).property('3qvideo')}
    renderer = Sitegeist.AssetSource.3QVideo:VideoPlayer {
        playoutId = ${ThreeQVideo.playoutIdForAsset(props.asset)}
        playerId = ${'video-' + node.identifier}

        muted = ${props.muted ? props.muted :  true}
        autoplay = ${props.autoplay ? props.autoplay : false}
        loop = ${props.loop ? props.loop : false}
        controls = ${props.controls ? props.controls : true}

    }
}
```

## Acknowledgements
Inspired by the format of @bwaidelich  and the [Pimcore Asset source](https://github.com/bwaidelich/Wwwision.Neos.AssetSource.Pimcore/)
