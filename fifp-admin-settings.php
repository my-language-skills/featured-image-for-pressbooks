<?php
/**
 *  EFP Customization settings include.
 *  This file is responsible for creation of setting section for this plugin in EFP settings page.
 *
 * @package           featured-image-for-pressbooks
 * @since             0.6 (file introduced)
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
    //section
    add_settings_section('featured_image_section',
                         'Featured images section',
                         '',
                         'theme-customizations');
    //field 1
    add_settings_field('fifp_disable_for_mobile',
                       'Featured images on mobile',
                       'fifp_mobile_option_callback',
                       'theme-customizations',
                       'featured_image_section');

    if ("source" == fifp_is_site_source()) { // show this field only in Source books.

         //field 2
        add_settings_field('fifp_import_featured_image_to_clones_button',
                          'Export to cloned books',
                          'fifp_import_fi_to_clones_button_callback',
                          'theme-customizations',
                          'featured_image_section');

        register_setting( 'theme-customizations-grp',
                          'fifp_import_fi_to_clones_button_callback');
     }
    register_setting( 'theme-customizations-grp',
                      'fifp_disable_for_mobile');
    add_option('fifp_disable_for_mobile',0);
 }

/**
 * Callback functions for monitoring and changing checbox state.
 *
 * @since 0.6
 *
 */
function fifp_mobile_option_callback(){
  $option = get_option( 'fifp_disable_for_mobile' );
	echo '<input name="fifp_disable_for_mobile" id="fifp_disable_for_mobile" type="checkbox" value="1" class="code" ' . checked( 1, $option, false ) . ' /> '._('Disable featured images on mobile').'';
}

/**
 * Callback functions for monitoring and changing checbox state.
 *
 * @since 0.6 (introduced), 0.7 (make functional)
 *
 */
function fifp_import_fi_to_clones_button_callback(){
    echo '<form method="post" action="">
            <input type="submit" class="button" name="import_fi_submit_btn" value="RUN" />
          </form>';
}

add_action( 'init', 'fifp_init_importing_process' );

/**
 * Function monitoring 'RUN' button activity. When clicked, initializes Main import function.
 *
 * @since 0.7
 *
 */
function fifp_init_importing_process() {
     if( isset( $_POST['import_fi_submit_btn'] ) ) {
          //header("Refresh:0; url=themes.php?page=theme-customizations");
          fifp_import_source_images();
     }
}
