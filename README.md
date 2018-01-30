Podcast plugin 0.7.1
====================
Simple podcast feed for media files. Based on the [original Yellow feed plugin](https://github.com/datenstrom/yellow-plugins/tree/master/feed). 

## How do I install this?

1. [Download and install Datenstrom Yellow](https://github.com/datenstrom/yellow/).
2. [Download plugin](https://github.com/schulle4u/yellow-plugin-podcast/archive/master.zip). If you are using Safari, right click and select 'Download file as'.
3. Copy `podcast.zip` into your `system/plugins` folder.

To uninstall delete the [plugin files](update.ini).

## How to use a podcast?

The podcast feed is available on your website as `http://website/podcast/` and `http://website/podcast/page:podcast.xml`. It's a feed for the entire website, only visible pages are included. To make a blog podcast feed open file `system/config/config.ini` and change `PodcastFilter: blog`. You can add a link to the podcast somewhere on your website. 

## How to configure a podcast?

The following settings can be configured in file `system/config/config.ini`:

`PodcastLocation` = podcast location  
`PodcastFileXml` = feed file name for RSS podcast feed  
`PodcastFilter` = podcast template filter  
`PodcastPaginationLimit` = number of entries to show per page  
`PodcastMimeType` = mimetype of included files, default is audio/mpeg (mp3)  
`PodcastImageUrl` = URL to your podcast image.  
`PodcastImageWidth` and `PodcastImageHeight` = image dimensions in pixel.  
`PodcastKeywords` = Some global Keywords (or tags) for your podcast.  
`PodcastExplicit` = Does your podcast contain explicit content? Default is no.  
`PodcastItunesBlock` = Block iTunes directory from listing your podcast, default is no.  
`PodcastCategory` and `PodcastSubcategory` = iTunes podcast category names. Currently only one category or one category and one subcategory is supported.  

Additionally, the following page metadata can be specified: 

`Mediafile` = URL to the podcast media file.  
`Duration` = the duration of the episode in the format HH:MM:SS (required for iTunes).  

*Note*: Only a media file is mandatory for the podcast. All other settings are optional, but are required for propper iTunes directory listing. 

## Example

Here is a blog page with included media content. 

```
---
Title: A new podcast episode
Published: 2018-01-01 12:45:00
Author: Steffen
Tag: cool, stuff
Template: blog
Mediafile: http://cdn.yourdomain.tld/podcast/files/episode1.mp3
Duration: 00:10:00
---
This is the article for your podcast episode.[--more--] It is recommended to use a page break to provide your listeners with a summary for their podcatchers. Furthermore you should link the media file [somewhere in this text](http://cdn.yourdomain.tld/podcast/files/episode1.mp3) for direct download. 
```

## Todo

* Dedicated podcast page template with audio player
* Extend `PodcastFilter` with a location feature, e.G. `PodcastFilterLocation: /my-podcast/`

## Developer

Datenstrom with modifications by Steffen Schultz

## See also

* [Audio plugin](https://github.com/schulle4u/yellow-plugin-audio/), useful to directly play the podcast on your website. 
