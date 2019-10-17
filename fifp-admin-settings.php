<?php
/**
 *  EFP Customization settings include.
 *  This file is responsible for creation of setting section for this plugin in EFP settings page.
 *
 * @package           featured-image-for-pressbooks
 * @since             0.6
 *
 */

 defined ("ABSPATH") or die ("Action denied!");

 if( !function_exists('is_plugin_active') ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

// initialize settings section only if conditions are valid

 if ((1 != get_current_blog_id()	|| !is_multisite())
      && is_plugin_active('pressbooks/pressbooks.php')
      && is_plugin_active('extensions-for-pressbooks/extensions-for-pressbooks.php')){

        add_action('admin_init','fifp_init_featured_image_section');
 }

/**
* Add settings section, settings fields and add_option to the DB.
*
* @since 0.6
*
*/
function fifp_init_featured_image_section(){

    add_settings_section('featured_image_section',
                         'Featured images section',
                         'featured_image_section_description',
                         'theme-customizations');

    add_settings_field('fifp_disable_for_mobile',
                       'Disable Featured image on mobile',
                       'fifp_mobile_option_callback',
                       'theme-customizations',
                       'featured_image_section');

    add_settings_field('fifp_import_featured_image_to_clones',
                       'Import featured images to clones',
                       'fifp_import_fi_to_clones_callback',
                       'theme-customizations',
                       'featured_image_section');

    register_setting( 'theme-customizations-grp',
 											'fifp_disable_for_mobile');

    register_setting( 'theme-customizations-grp',
                      'fifp_import_featured_image_to_clones');

    add_option('fifp_disable_for_mobile',0);
    add_option('fifp_import_featured_image_to_clones',0);
 }

 /**
 * Section description
 *
 * @since 0.6
 *
 */
function featured_image_section_description(){
  echo '<p></p>';
}

/**
 * Callback functions for monitoring and changing checbox state.
 *
 * @since 0.6
 *
 */
function fifp_mobile_option_callback(){
  $option = get_option( 'fifp_disable_for_mobile' );
	echo '<input name="fifp_disable_for_mobile" id="fifp_disable_for_mobile" type="checkbox" value="1" class="code" ' . checked( 1, $option, false ) . ' /> Check to disable featured image on mobile devices.';
}

function fifp_import_fi_to_clones_callback(){
  $option = get_option( 'fifp_import_featured_image_to_clones' );
	echo '<input name="fifp_import_featured_image_to_clones" id="fifp_import_featured_image_to_clones" type="checkbox" value="1" class="code" ' . checked( 1, $option, false ) . ' disabled="disabled" /> Check to enable automatical import of featured images from Original book to its clones. (NOT WORKING YET)';
}
