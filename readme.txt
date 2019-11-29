=== Feature image for PressBooks ===
Contributors: colomet, danzhik, huguespages
Donate link: https://opencollective.com/mylanguageskills
Tags: multisite, pressbooks, images, media, thumbnail, feature-image, wordpress plugin
Requires at least: 3.0.1
Tested up to: 5.2.2
Requires PHP: 5.6
Stable tag: 0.7
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

You can have features images and images with the post width size in a PressBooks installation

== Description ==

This plugin provides the possibility to use images adapted to a PressBooks installation. Features:
* Add support of thumbnails in PressBooks CPTs.
* Use an image as Featured Image of your PressBooks CPTs.
* New images sizes with the post max size (narrow - 508px, standard - 688px, wide - 832px)
* New sizes in attachment display settinngs. By default is selected the size of the blog.
* Option to deactivate feature images at mobiles.

**Only works with [multisite](https://wordpress.org/support/article/create-a-network/) installation!**
**Only works with [PressBooks](https://github.com/pressbooks/pressbooks) installation!**

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
3. By the configuration of the post size (between narrow, standard and wide), the required image is selected by default.


= Setup =
* A new size is available in (and selected by default) in the attachment display settings.
* A new metabox is available in each page where an image link can be used or an image can be upload (where the size would be automatic selected as long as is as big as the page size).


== Frequently Asked Questions ==

= I have a feature request, I've found a bug, a plugin is incompatible... =

Please visit [the support forums](https://wordpress.org/support/plugin/xxxxxxxx)

= I am a developer; how can I help? =

Any input is much appreciated, and everything will be considered.
Please visit the [GitHub project page](https://github.com/my-language-skills/featured-image-for-pressbooks) to submit issues or even pull requests.

= Must I create the images in any spacial size? =

No. As long as the images are as big as the post size (narrow - 508px, standard - 688px, wide - 832px), the plugin will be able to create the new size versions.

If the image is not as big as the content area, the image will be centered with not content in left or right side.


= What if the images are upload before the plugin is activated? =

Will not work as the images get the cut with the new sizes after the upload. [Regenerate thumbnais](https://es.wordpress.org/plugins/tags/thumbnail/) or similar plugins are required in order to create the thumbnails again in all the upload images.

= What if I not longer need the plugin? =

The images would be created in that new sizes. You can just let them in the server or to search the suffix -508; -688; -832 and to delete those images

== Screenshots ==


== Changelog ==

=== 0.8 ===
* **ENCHANCEMENTS**
  * Functions renaming (prefixes)
  * Minor text changes and visual enhancements

* **REMOVED**
  * Empty section callback function

=== 0.7 ===
* Major rework (upgrade) of plugin functionality

* **ADDITIONS**
  * Featured images are now able to be imported from Source to all of its clones from EFP Customization settings.
  * Bottom field of featured image metabox in post-edit page changed to display information about availability of source images. More information in documentation-technical.

* **REMOVED**
  * Functionalities related to adding featured images by URL.

* **ENCHANCEMENTS**
  * documentation
  * files renamed, some functions relocated

* **List of Files revised**
  * fifp-admin-settings.php
  * featured-iamges-for-pressbooks.php

=== 0.6 ===
* **ADDITIONS**
  * Featured images section in EFP Customization settings page.
  * Functionality to disable displaying featured images on mobile devices for the book.

* **ENCHANCEMENTS**
  * documentation

* **List of Files revised**
  * featured-image-for-pressbooks.php
  * added fifp-admin-settings.php

=== 0.5 ===
* **REMOVED**
    *  Autoloader

=== 0.4 ===
* **ENCHANCEMENTS**
  * New sizes in attachment display settings. By default is selected the size of the blog.

* **ADDITIONS**
  * Automatically set the image Title, Alt-Text, Caption & Description upon upload

* **List of Files revised**
       * featured-image-for-pressbooks.php

=== 0.3 ===
* **ENCHANCEMENTS**
    * Set good size

=== 0.2 ===
* **ADDITIONS**
    * Activate the post-thumbnails in theme

* **ENCHANCEMENTS**
    * New file name
    * Change autoloader parameters

=== 0.1 ===
* **ADDITIONS**
    * Use a media stored image as a featured image
    * Use an external image as a featured image via URL
    * Set appropriate featured image size according to Pressbooks books sizes
    * Monitor featured images from posts administration area



== Upgrade Notice ==

= 0.5 =
First release

== Disclaimers ==

The Featured image for PressBooks plugin is supplied "as is" and all use is at your own risk.
