=== Feature image for PressBooks ===
Contributors: colomet, danzhik, huguespages
Donate link: https://opencollective.com/mylanguageskills
Tags: multisite, pressbooks, images, media, thumbnail, feature-image, wordpress plugin
Requires at least: 3.0.1
Tested up to: 5.2.2
Requires PHP: 5.6
Stable tag: 0.5
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

You can have features images and images with the post width size in your PressBooks site

**Only works with multisite installation!**
**Only works with PressBooks installation!**

== Description ==

This plugin provides the possibility to use images adapted to a PressBooks installation. Features:
* Use an external image as Featured Image of your PressBooks CPTs.
* Add support of thumbnails in PressBooks CPTs.
* Add administration columns to check featured image status.
* New images with the post max size (narrow - 508px, standard - 688px, wide - 832px)
* New sizes in attachment display settinngs. By default is selected the size of the blog.

== Installation ==

= This plugin requires: =

This sections describes the requirements of the plugin.

* Wordpress Multisite installation
* PressBooks plugin activated
* Feature image functionality may require an integration with your theme.

= Installation instructions: =

This section describes how to install the plugin and get it working.


1. Clone (or copy) this repository folder `featured-image-for-pressbooks` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' screen in WordPress
*****3. By the configuration of the post size (between narrow, standard and wide), the required image is selected by default.*******


= Setup =
* A new size is available in (and selected by default) in the attachment display settings  (que pasa si es más pequeña???)
* A new metabox is available in each page where an image link can be used or an image can be upload (where the size would be automatic selected as long as is as big as the page size).
(hace falta un warming donde solo un mínimo tamaño sea cargado)
(y cual elige? hay opciones también o solo coge el link????????????????????????????????. 


== Frequently Asked Questions ==

= I have a feature request, I've found a bug, a plugin is incompatible... =

Please visit [the support forums](https://wordpress.org/support/plugin/xxxxxxxx)

= I am a developer; how can I help? =

Any input is much appreciated, and everything will be considered.
Please visit the [GitHub project page](https://github.com/sybrew/the-seo-framework) to submit issues or even pull requests.

= Must I create the images in any spacial size? =

No. As long as the images are as big as the post size (narrow - 508px, standard - 688px, wide - 832px), the plugin will be able to create the new size versions.

= What if the images are upload before the plugin is activated? =

Will not work as the images get the cut with the new sizes after the upload. []() or similar plugins are required in order to create the thumbnails again in all the upload images.

= What if I not longer need the plugin? =

The images would be created in that new sizes. You can just let them in the server or to search the suffix -508; -688; -832 and to delete those images

== Screenshots ==


== Changelog ==

= 0.5 =
* First release


== Upgrade Notice ==

= 0.5 =
First release

== Disclaimers ==

The Featured image for PressBooks plugin is supplied "as is" and all use is at your own risk.