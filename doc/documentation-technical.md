# Featured Image for Pressboks CPTs Technical Documentation

## Installation and Upgrades
1. Clone (or copy) this [repository](https://github.com/my-language-skills/pressbooks-featured-image)(not official release yet) to the ```/wp-content/plugins/``` directory
1. Activate the Plugin through the 'Plugins' screen in WordPress

For upgrades, download the last stable version from Github, delete from FTP the old Plugin and install the new one.

## Theme support 
In order to properly utilize any feature of Featured Image for Pressboks CPTs the theme or child theme of Pressbooks you are goin gto use on your web-site **must** support featured images.

If you would like to use our plugin with the one, which does not have featured images included support, below is the guide how to make your theme compatible with them.

### Featured images compatibility

2. In ```/wp-content/themes/your-theme-directory(replace with your theme directory name)/functions.php``` file add the following line of code in the end of file or inside the function which is hooked for ```after_setup_theme``` action (you will need to locate it):
```add_theme_support('post-thumbnails')```.
2. Since this moment with plugin activated, you should be able see new **Thumbnail** (=Featured Image) column in posts administration area and **featured image** metabox in post editing page, but no changes in a front-end of web-site yet.
2. In order for your web-site to show featured images to end-users, locate the template files or template partials files standing for single post markup generation.
2. Add the following lines of code to ```your-needed-template(replace with the name of template where featured image will appear).php```:

```php
    if ( has_post_thumbnail() ) {
   		$option = get_option("pressbooks_theme_options_web");
   		if ($option['webbook_width'] == '30em'){
   			the_post_thumbnail('featured-narrow');
   		}
   		if ($option['webbook_width'] == '40em'){
   			the_post_thumbnail('featured-standard');
   		}
   		if ($option['webbook_width'] == '48em'){
   			the_post_thumbnail('featured-wide');
   		}
   	}
```
 **Note:** The code above in order to work properly should be located inside a loop if located in template. If you are going to locate in partial template, check whether this partial template is called inside loop 
* If you are using a child theme, which does not have its own templates specified, you will need to clone those templates into your theme directory with according folder hierarchy and add the code above to cloned files in a location you would like your featured images to appear. 



---
Back to [Readme](../README.md).