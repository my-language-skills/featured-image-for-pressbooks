# Featured Image for Pressboks CPTs Technical Documentation

## Installation and Upgrades
1. Clone (or copy) this [repository](https://github.com/my-language-skills/pressbooks-featured-image)(not official release yet) to the ```/wp-content/plugins/``` directory
1. Activate the Plugin through the 'Plugins' screen in WordPress

For upgrades, download the last stable version from Github, delete from FTP the old Plugin and install the new one.

## Theme support
In order to properly utilize any feature of Featured Image for Pressboks CPTs the theme or child theme of Pressbooks you are going to use on your web-site **must** support featured images.

If you would like to use our plugin with the one, which does not have featured images included support, below is the guide how to make your theme compatible with them.

### Featured images compatibility

2. In ```/wp-content/themes/your-theme-directory(replace with your theme directory name)/functions.php``` file add the following line of code in the end of file or inside the function which is hooked for ```after_setup_theme``` action (you will need to locate it):
```add_theme_support('post-thumbnails')```.
2. Since this moment with plugin activated, you should be able see new **Thumbnail** (=Featured Image) column in posts administration area and **featured image** metabox in post editing page, but no changes in a front-end of web-site yet.
2. In order for your web-site to show featured images to end-users, locate the template files or template partials files standing for single post markup generation.
2. Add the following lines of code to ```your-needed-template(replace with the name of template where featured image will appear).php```:

```php

<?php
if (is_plugin_active('featured-image-for-pressbooks/featured-image-for-pressbooks.php')){
	if(!(wp_is_mobile() && fifp_is_featured_image_disabled())){
		// condition to check if displaying featured images is not disabled for mobile devices
			?>
				<div class="featured_image" >
					<?php
						if ( has_post_thumbnail() || fifp_has_ext_thumbnail()) { // check if some thumbnail exists for this post
								$fi_info = fifp_get_fi_info();
								if ( "print_local_fi" == $fi_info) {		// if site is source or thumbnail saved locally print from local
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
								} else {					// else print from external source (from source book)
									$source_fi_id = $fi_info;
									switch_to_blog(get_option( '_ext_source_id'));	// switch to source blog and get featured_image from there
										$option = get_option("pressbooks_theme_options_web");
										if ($option['webbook_width'] == '30em'){
											echo wp_get_attachment_image($source_fi_id, 'featured-narrow' );
										}
										if ($option['webbook_width'] == '40em'){
											echo wp_get_attachment_image($source_fi_id, 'featured-standard' );
										}
										if ($option['webbook_width'] == '48em'){
											echo wp_get_attachment_image($source_fi_id, 'featured-wide' );
										}
									restore_current_blog();
								}
						}
						?>
				</div>
			<?php
	 } } ?>

```
 **Note:** The code above in order to work properly should be located inside a loop if located in template. If you are going to locate in partial template, check whether this partial template is called inside loop
* If you are using a child theme, which does not have its own templates specified, you will need to clone those templates into your theme directory with according folder hierarchy and add the code above to cloned files in a location you would like your featured images to appear.

 **IMPORT thumbnails SOURCE -> CLONE:**
 * Since v0.7 of the featured_images_for_pressbooks plugin it is possible to populate thumbnails from the source to its clones.
 * Functionality of the plugin generates SOURCE -> CLONE relationships across multisite and based on this data is able to set thumbnails in chapter/part/front_matter/appendix posts.

*WORKFLOW:*
 1. We set thumbnail images we want to use in SOURCE BOOK in the edit-post page featured image metabox.
 2. After we are done, go to EFP Customization setting and RUN 'Import featured images to the book clones' button and thumbnails set in SOURCE book are now available in CLONE book posts.
 3. In case we do not want to use specific thumbnail inherited from the SOURCE in specific post, we can override this by selecting thumbnail locally in specific post.
 4. Once import was done, when thumbnail is changed in SOURCE it changes in all the clones in related posts automatically.

 * Since thumbnail in CLONES is pulled from the SOURCE site (file), when this file is deleted it will no longer be available in the CLONES too.
 * In CLONE edit-post page featured image metabox we are informed about current state of thumbnail state of import and set.

---
Back to [Readme](../README.md).
