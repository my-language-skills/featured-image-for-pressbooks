<?php

/**
 * Featured Image for PressBooks
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * Dictionary of words with same meanings used across:
 * 	site == blog
 * 	source == original book
 * 	fi == featured image
 * 	fifp == featured_image_for_pressbooks
 *
 * @link              https://github.com/my-language-skills/featured-image-for-pressbooks
 * @since             0.1
 * @package           featured-image-for-pressbooks
 *
 * @wordpress-plugin
 * Plugin Name:       Featured Image for PressBooks
 * Plugin URI:        https://github.com/my-language-skills/featured-image-for-pressbooks
 * Description:       Use an external image as Featured Image of your post/page, add support of thumbnails in PressBooks CPTs and add administration columns to check featured image status.
 * Version:           0.7
 * Pressbooks tested up to: 5.10
 * Author:            My Language Skills team
 * Author URI:        https://github.com/my-language-skills/
 * License:           GPL 3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       featured-image-for-pressbooks
 * Domain Path:       /languages
 * Network: 					True
 */

include_once plugin_dir_path( __FILE__ ) . "fifp-admin-settings.php";

/**
 * Checking whether provided URL leads to image file of jpeg, jpg, gif, png formats.
 *
 * @param string $url URL to check
 *
 * @return bool result of check
 *
 * @since 0.1
 *
 */
function url_is_image( $url ) {

	//check if URL is valid in general format
	if ( ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
		return FALSE;
	}

	//declare allowed file formats
	$ext = array( 'jpeg', 'jpg', 'gif', 'png' );

	//get path information on provided file from URL
	$info = (array) pathinfo( parse_url( $url, PHP_URL_PATH ) );

	//check if URL leads to file and file has allowed extension and return a result
	return isset( $info['extension'] )
	       && in_array( strtolower( $info['extension'] ), $ext, TRUE );
}

/**
 * Add support of featured images to PressBooks post types.
 *
 * @since 0.1
 *
 */
function add_thumbnail_support () {
	add_post_type_support( 'chapter', 'thumbnail' );
	add_post_type_support( 'part', 'thumbnail' );
	add_post_type_support( 'front-matter', 'thumbnail' );
	add_post_type_support( 'back-matter', 'thumbnail' );
}

//initiate featured images support
add_action('init', 'add_thumbnail_support');

add_filter( 'admin_post_thumbnail_html', 'fifp_thumbnail_source_field' );
//<

/****** Add Thumbnails in Manage Posts/Pages List ******/
if ( !function_exists('AddThumbColumn')) {

	/**
	 * Adding featured image column to administration area.
	 *
	 * @param array $cols existing columns
	 *
	 * @return mixed updated columns
	 *
	 * @since 0.1
	 *
	 */
	function AddThumbColumn( $cols ) {

		$cols['thumbnail'] = __( 'Thumbnail' );

		return $cols;
	}
}

/**
 * Set values of featured images columns in admin area.
 *
 * @param string $column_name column name
 * @param int $post_id post ID
 *
 * @since 0.1
 *
 */
function AddThumbValue($column_name, $post_id) {


	if ( 'thumbnail' == $column_name ) {

		//get featured image id
		$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );

		//if featured image is set from local media
		if ($thumbnail_id && $thumbnail_id !== 'by_url') {
			echo __('&#10004; from media');
		} elseif ($thumbnail_id) {
			//if featured image is set wih external URL
			echo __('&#10004; external');
		} else {
			//if no featured image set
			echo __('—');
		}
	}
}
//> Apply new columns to PressBooks CPTs

	//for chapter CPT
	add_filter( 'manage_chapter_posts_columns', 'AddThumbColumn' );
	add_action( 'manage_chapter_posts_custom_column', 'AddThumbValue', 10, 2 );

	//for part CPT
	add_filter( 'manage_part_posts_columns', 'AddThumbColumn' );
	add_action( 'manage_part_posts_custom_column', 'AddThumbValue', 10, 2 );

	//for front-matter CPT
	add_filter( 'manage_front-matter_posts_columns', 'AddThumbColumn' );
	add_action( 'manage_front-matter_posts_custom_column', 'AddThumbValue', 10, 2 );

	//set columns for back-matter CPT
	add_filter( 'manage_back-matter_posts_columns', 'AddThumbColumn' );
	add_action( 'manage_back-matter_posts_custom_column', 'AddThumbValue', 10, 2 );
//<

//adding new featured images sizes
add_image_size( 'featured-narrow', 508 );
add_image_size( 'featured-standard', 688 );
add_image_size( 'featured-wide', 832 );

// activate the post-thumbails
add_action( 'after_setup_theme', function () {
	add_theme_support( 'post-thumbnails' );
} );


/**
 * Creation of the new size.
 *
 *
 * @since 0.3
 * @since 0.4 Change value of image_size
 *
 */

function use_new_image_size() {
    if ( function_exists( 'add_image_size' ) ) {
        add_image_size( 'featured-narrow', 508, 0, false );
				add_image_size( 'featured-standard', 688,0, false  );
				add_image_size( 'featured-wide', 832,0, false  );
    }
}
add_action( 'after_setup_theme', 'use_new_image_size' );


/**
 * Function to register and change the first one connect.
 *
 * @param string $sizes size
 *
 * @since 0.4
 * @return '$sizes'
 *
 */
function function_register($sizes){
	// creation of data
	$temp = $sizes['thumbnail'];
	$temp1 = $sizes['medium'];
	$temp2 = $sizes['large'];
	$temp3 = $sizes['full'];

	// unset data
 unset( $sizes['thumbnail']);
 unset( $sizes['medium']);
 unset( $sizes['large']);
 unset( $sizes['full']);

 // creation of the new order
 $sizes['thumbnail'] = $temp;
 $sizes['medium'] = $temp1;
 $sizes['large'] = $temp2;
 $sizes['full'] = $temp3;

	return $sizes;

}
/**
 * Create and add the new size in the select of administration.
 *
 * @param string $sizes size
 *
 * @since 0.4
 * @return '$sizes' '$custom_sizes'
 *
 */

function create_custom_image_size($sizes){
	$options = get_option( 'pressbooks_theme_options_web' );
		$width   = $options['webbook_width'];
		if ($width == '40em') {
					$custom_sizes = array(
			'featured-standard' => 'Standard');
		}
		if ($width == '30em') {
			$custom_sizes = array(
				'featured-narrow' => 'Narrow'
			);
		}
		if ($width == '48em') {
			$custom_sizes = array(
				'featured-wide' => 'Wide'
			);
		}

    return array_merge( $sizes, $custom_sizes );
}
// add new filter of the new size
add_filter('image_size_names_choose', 'create_custom_image_size');
// add new change the older of the list
add_filter('image_size_names_choose', 'function_register');
// add uptade the size of the default size
update_option( 'image_default_size', 'create_custom_image_size' );

add_action( 'add_attachment', 'my_set_image_meta_upon_image_upload' );

/**
 * Automatically set the image Title, Alt-Text, Caption & Description upon upload http://brutalbusiness.com/automatically-set-the-wordpress-image-title-alt-text-other-meta/ for adding the tags and categories: https://wordpress.org/plugins/seo-image/.
 *
 * @param string $post_ID post_id
 *
 * @since 0.4
 *
 */
function my_set_image_meta_upon_image_upload( $post_ID ) {
	// Check if uploaded file is an image, else do nothing
	if ( wp_attachment_is_image( $post_ID ) ) {
		$my_image_title = get_post( $post_ID )->post_title;
		// Sanitize the title:  remove hyphens, underscores & extra spaces:
		$my_image_title = preg_replace( '%\s*[-_\s]+\s*%', ' ',  $my_image_title );
		// Sanitize the title:  letters lower case:
		$my_image_title = strtolower( $my_image_title );
		// Create an array with the image meta (Title, Caption, Description) to be updated
		// Note:  comment out the Excerpt/Caption or Content/Description lines if not needed
		$my_image_meta = array(
			'ID'		=> $post_ID,			// Specify the image (ID) to be updated
			'post_title'	=> $my_image_title,		// Set image Title to sanitized title
		//	'post_excerpt'	=> $my_image_title,		// Set image Caption (Excerpt) to sanitized title ********
			'post_content'	=> $my_image_title,		// Set image Description (Content) to sanitized title
		);
		// Set the image Alt-Text
		update_post_meta( $post_ID, '_wp_attachment_image_alt', $my_image_title );
		// Set the image meta (e.g. Title, Excerpt, Content)
		wp_update_post( $my_image_meta );
	}
}

/**
 * Function called from the front-end in order to determine if mobile featured images are disabled or not.
 *
 * @return '$option'
 * @since 0.6
 *
 */
function fifp_is_featured_image_disabled(){
  return $option = get_option( 'fifp_disable_for_mobile' );
}

/**
 * Function for printing proper output for the source field of the featured image metabox in post-edit page
 *
 * @return '$html'
 * @since 0.7
 *
 */
function fifp_thumbnail_source_field($html){
	global $post;
	global $wpdb;

	$source_site_id = get_option( '_ext_source_id');  // get source_id of the current post
	$slug = $post->post_name;													// get post_name of the post

	switch_to_blog($source_site_id );
		$post_query_result = $wpdb->get_row("SELECT ID FROM {$wpdb->prefix}posts WHERE post_name = '{$slug}'"); // get source post of current post

		if (isset($post_query_result)){
			$source_post_id_of_post_name_slug = (int) $post_query_result->ID;   // related ID of the clone
		}

		$source_fi_id = get_post_meta($source_post_id_of_post_name_slug, '_thumbnail_id', TRUE ) ? : "";
		$source_fi = wp_get_attachment_image($source_fi_id, 'medium' );
	restore_current_blog();

	// set variables for following conditions
	$locally_saved_source_fi_id = get_post_meta($post->ID, '_thumbnail_ext_source_id', TRUE );
	$option_ext = get_option( '_ext_source_id');
	$option_local = get_post_meta($post->ID, '_thumbnail_id', TRUE );
	$source_or_clone = fifp_is_site_source();

	if ("source" == $source_or_clone){
		$html .= '<hr> <br><b>' . __( 'This book is source', 'txtdomain' ) . '</b>';
			return $html;

		}	elseif (empty($option_ext)) {
		 $html .= '<hr> <br><b>' . __( 'Run import to display source image', 'txtdomain' ) . '</b>';
		 	return $html;

		} elseif (empty($source_fi)) {
		$html .= '<hr> <br><b>' . __( 'Source image not set in source', 'txtdomain' ) . '</b>';
			return $html;

		} elseif (empty($locally_saved_source_fi_id)){
		$html .= '<hr><br> <b>' . __( 'Source image is set in source, but is not imported', 'txtdomain' ) . '</b>';
			return $html;

		} elseif (!empty($option_local)){
		$html .= '<hr> <br><b>' . __( 'Source image is imported but is not set', 'txtdomain' ) . '</b><br><br>';
		$html .= $source_fi;
			return $html;

		}  else {
		$html .= '<hr><br> <b>' . __( 'Source image is imported and is set', 'txtdomain' ) . '</b><br><br>';
		$html .= $source_fi;
			return $html;
}
	return $html;
}


/**
 * Main procedural function handling import of featured images from source to all of its clones (post by post).
 * This function is called by clicking on the button in EFP Customization setting page of a source book.
 *
 * @since 0.7
 *
 */
function fifp_import_source_images(){
    	global $post;
      global $wpdb;
      $post_types = ['front-matter','chapter','part','back-matter'];

      $current_blog_clones = fifp_get_clones();       // first get all the clones of currently opened source

    // go through each clone of the current source
    foreach ($current_blog_clones as $key=>$clone){
			switch_to_blog( $clone );
        $table_name = $wpdb->prefix . 'postmeta';  //set tablename prefix
        $postmeta_posts_url = $wpdb->get_results("SELECT meta_value FROM $table_name WHERE meta_key = 'pb_is_based_on'");  // get URLs of the pb_is_based_on of all current clone posts

	      // go through each post of the current clone which has meta_key pb_is_based_on
	      foreach ($postmeta_posts_url as $post_obj){

	        $postmeta_post_ID = $wpdb->get_results("SELECT post_id FROM $table_name WHERE meta_key = 'pb_is_based_on'");

	        $url = $post_obj->{'meta_value'};   // extract URL from object
	        $parsed_url = wp_parse_url( $url ); // parse URL
          $path = $parsed_url['path'];  			// get just path from URL
          $path = substr($path, 0, -1); 			// remove ending slash
	        $pos = strrpos($path, "/"); 				// get position of current last slash in the string
	        $slug = substr($path, $pos);				// use this positon for begin of getting post_name
	        $slug = trim($slug, "/");

	        $current_clone_source_id = fifp_get_clone_source_id(); // get source of the current clone

	        switch_to_blog($current_clone_source_id);  // switch to the blog of the source and get the ID of the exctracted post_name slug
	          $post_query_result = $wpdb->get_row("SELECT ID FROM {$wpdb->prefix}posts WHERE post_name = '{$slug}'");

	          if (isset($post_query_result)){
	            $source_post_id_of_post_name_slug = (int) $post_query_result->ID; //ID source
	          }
	        restore_current_blog();     // escape source blog

          unset($post_query_result);

          $post_query_result = $wpdb->get_row("SELECT ID FROM {$wpdb->prefix}posts WHERE post_name = '{$slug}'"); //now same for the clone book

          if (isset($post_query_result)){
            $clone_post_id_of_post_name_slug = (int) $post_query_result->ID;   //related ID of the clone
          }

          if (isset($source_post_id_of_post_name_slug) && isset($clone_post_id_of_post_name_slug)){ // if we obtained both IDs continue
	          switch_to_blog($current_clone_source_id); //switch to source again and get featured image for current clone post
	            $source_fi_id = get_post_meta( $source_post_id_of_post_name_slug, '_thumbnail_id', true );
	          restore_current_blog(); // escape source blog

	          if (!empty($source_fi_id)){ // if source post have featured_image paste its ID to the same post of the clone
	            update_post_meta( $clone_post_id_of_post_name_slug, '_thumbnail_ext_source_id', $source_fi_id, false); // update post meta of the clone with newly obtained url of the FI from the source
	          } else {
	            delete_post_meta( $clone_post_id_of_post_name_slug, '_thumbnail_ext_source_id' );
	          }
	        	unset($source_fi_link);
      		}
				}
				if (empty(get_option( '_ext_source_id'))){
					add_option( '_ext_source_id', $current_clone_source_id );
				} else{
					update_option('_ext_source_id', $current_clone_source_id ); // save value of the source id to the clone for later purposes
				}
	      $current_clone_source_id = fifp_get_clone_source_id();

				update_option('_ext_source_id', $current_clone_source_id ); // save value of the source id to the clone for later purposes

	      unset($clone_post_id_of_post_name_slug);
	      unset($source_post_id_of_post_name_slug);
	      unset($fi_link);
	      unset($post_query_result);
	      unset($current_clone_source_id);
	      unset($url);

	    restore_current_blog();
    }
}

/**
 * Based on the pb_is_based_on in book-info post, function gets ID of the source book
 *
 * @return '$this_clone_source_blog_id'
 * @since 0.7
 *
 */
function fifp_get_clone_source_id(){
  	global $wpdb;
    $table_name = $wpdb->prefix . 'posts'; 						// set prefix of the table
    $book_info_id = $wpdb->get_row("SELECT ID FROM $table_name WHERE post_name = 'book-information';"); // get ID of the book_info post

    if ($book_info_id){
      $book_info_id = get_object_vars($book_info_id); // extract content of the object
      $book_info_id = reset($book_info_id);           // get first value of the object

      $bookinfo_basedon_url = get_post_meta( $book_info_id, 'pb_is_based_on', true ); //based on book-info ID get pb_is_based_on URl

      if ($bookinfo_basedon_url){
        $urlparse = parse_url($bookinfo_basedon_url); // If existsparse url
        $urlparse_host = $urlparse["host"];           // get 'host' of the url
        $urlparse_path = $urlparse["path"];           // get 'path' of the url
        $this_clone_source_blog_id = get_blog_id_from_url( $urlparse_host, $urlparse_path . '/' );

        return $this_clone_source_blog_id;  // function returns ID of the SOURCE blog
      }
    }
}

/**
 * Based on fifp_find_source_clone_relatinoships() extract clones of the source
 *
 * @return '$current_blog_clones' arr
 * @since 0.7
 *
 */
function fifp_get_clones(){

	// first we need to get relationships between current source and its clones
  $source_site_relation = fifp_find_source_clone_relatinoships();
  $current_blog_clones = array();				// declare array

  $currently_opened_blog_ID = get_current_blog_id();

	// loop through each relation SOURCE => CLONE and get the clones related to the current source
  foreach($source_site_relation as $key=>$arr){
    if ($currently_opened_blog_ID == implode(array_keys($arr))){
      $clones =  implode(array_values ($arr)); // get array value (clone ID)
      $current_blog_clones[] = $clones;        // add it to the array and retur all current blog clones
       }
    }
  return $current_blog_clones;
}

/**
 * Function responsible for accumulating of all the SOURCE -> CLONE relationships.
 *
 * @return '$source_site_relation' asociative array SOURCE => CLONE
 * @since 0.7
 *
 */
function fifp_find_source_clone_relatinoships(){
	global $wpdb;

  $post_types = ['metadata','front-matter','chapter','part', 'back-matter']; // set used post_types
  $args = array(
      'fields'          => 'ids',
      'posts_per_page'  => -1,
      'post_type' => $post_types
    );

  $sites = get_sites($args); // get all the sites IDs based on the criteria

  // initialize arrays
  $clones_sources_blog_ids = array();
  $no_pb_blogs_ids = array();
  $source_site_relation = array();

  // go through each site
  foreach ( $sites as $site ) {
    switch_to_blog( $site );  																	// switch to the current site
      $this_clone_source_blog_id = fifp_get_clone_source_id();  // get its SOURCE book ID
      $this_clone_blog_id = get_current_blog_id();          		// get current clone own ID

      if($this_clone_source_blog_id && $this_clone_blog_id){
        $source_site_relation[][$this_clone_source_blog_id] = $this_clone_blog_id; // merge those two values into the array
      }
    restore_current_blog();
  }
  return $source_site_relation;
}

/**
 * Function for determining what featured_image shoud be printed. Called from the front-end (theme).
 * IF book is source or if local thumbnail (featured_image) is set:
 * @return "print_local_fi"
 * ELSE get ID of the source fi and return it:
 * @return "$source_fi_id"
 * @since 0.7
 *
 */
function fifp_get_fi_info(){
  	global $wpdb;
		global $post;

  	$source_site_id = get_option( '_ext_source_id');
		$thumb_id = get_post_meta( $post->ID, '_thumbnail_id', true );

    if (empty($source_site_id) || !empty($thumb_id)){ // if it has 'pb_is_post_meta' meta_key return 'print_local_fi'
			return "print_local_fi";

    } else {
				$slug = $post->post_name;

				switch_to_blog($source_site_id );
					$post_query_result = $wpdb->get_row("SELECT ID FROM {$wpdb->prefix}posts WHERE post_name = '{$slug}'"); // get source post of current post

					if (isset($post_query_result)){
						$source_post_id_of_post_name_slug = (int) $post_query_result->ID;   // get ID of the source fi
					}

					$source_fi_id = get_post_meta($source_post_id_of_post_name_slug, '_thumbnail_id', TRUE ) ? : "";
				restore_current_blog();

				if (empty($source_fi_id)){
					return;
				}

				return $source_fi_id;
			}
}

/**
 * Function that determines if post have or have not external thumbnail set. Called from front-end.
 *
 * @return '0' or '1'
 * @since 0.7
 *
 */
function fifp_has_ext_thumbnail(){
	global $post;
	$ext_thumb = get_post_meta($post->ID, '_thumbnail_ext_source_id', TRUE );
	if (!empty($ext_thumb)){
		return 1;
	}	else {
		return 0;
	}
}

/**
 * Function that determines if site is source or is not.
 *
 * @return 'source' or 'clone'
 * @since 0.7
 *
 */
function fifp_is_site_source(){
  global $wpdb;
  $table_name = $wpdb->prefix . 'posts'; // set prefix of the table
  $book_info_id = $wpdb->get_row("SELECT ID FROM $table_name WHERE post_name = 'book-information';"); //get ID of the book_info post

  if ($book_info_id){
    $book_info_id = get_object_vars($book_info_id); // extract content of the object
    $book_info_id = reset($book_info_id);           // get first value of the object
    $bookinfo_basedon_url = get_post_meta( $book_info_id, 'pb_is_based_on', true ); // based on this ID find book_info post_meta

    if (empty($bookinfo_basedon_url)){
      return "source";
    } else {
      return "clone";
    }
  }
}
