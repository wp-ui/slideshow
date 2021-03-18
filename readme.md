# WP UI: Slideshow

Simple slideshows for your Wordpress site, using shortcodes. Supports data from [ACF](https://www.advancedcustomfields.com/) fields. 


## Install

1. Upload `ui-slideshow` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress


## Dependencies

* [Backbone UI: Slideshow](http://github.com/backbone-ui/slideshow/)


## Usage

The plugin creates the shortcode _ui-slideshow_ which you can enter in your content, for example: 
```
[ui-slideshow ids="23,63,12,57" autoplay="true" autoloop="true" lazyload="true" timeout="4" view="slideshow"]


## Params

* **acf**: A custom field created on the post, containing a list of gallery assets 
* **ids**: Comma-separated numbers of the ids of gallery assets
* **post**: Specify a different post (than the current page) to get the assets from
* **randomize**: The slide order is randomized onload
* **view**: Override the default rendering with your own custom view fragment


## Options

The slideshow itself supports these options: 

* **autoloop**: Navigation buttons and autoplay loops around the slides
* **autoplay**: The slideshow switches slides automatically, based on the _timeout_ option
* **direction**: [left/right] The direction the autoplay moves
* **draggable**: Adds manual dragging of slides used mostly on touchscreen devices
* **height**: Set height size of slideshow
* **lazyload**: Load the slides after the slideshow is initiated
* **timeout**: The amount of seconds to move to the next slide, used in autoplay
* **width**: Set width size of slideshow

## Examples 

...


## Credits

Created by Makis Tracend ( [@tracend](http://github.com/tracend) )

Distributed through [Makesites.org](http://makesites.org/)

Released under the [MIT license](http://makesites.org/licenses/MIT)

