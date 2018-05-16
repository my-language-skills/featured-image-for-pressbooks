<?php

/*
 * Plugin Name: Featured Image for PressBooks CPTs
 * Description: Use an external image as Featured Image of your post/page, add support of thumbnails in PressBooks CPTs and add administration columns to check featured image status.
 * Version: 0.1
 * Author: Daniil Zhitnitskii
 * Author URI: https://www.linkedin.com/in/daniil-zhitnitskii/
 */

/**
 * Checking whether provided URL leads to image file of jpeg, jpg, gif, png formats
 *
 * @param string $url URL to check
 *
 * @return bool result of check
 *
 * @since 0.1
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
 * Add URL field to Featured Image metabox
 *
 * @param string $html Featured Image metabox HTML code
 *
 * @return string Featured Image metabox HTML code with URL field
 *
 * @since 0.1
 */
function thumbnail_url_field( $html ) {

	//declae global variable $post
	global $post;

	//try to retrieve metadata with image URL if exists
	$value = get_post_meta( $post->ID, '_thumbnail_ext_url', TRUE ) ? : "";

	//nonce system check creation
	$nonce = wp_create_nonce( 'thumbnail_ext_url_' . $post->ID . get_current_blog_id() );

	//generate HTML code for URL field
	$html .= '<input type="hidden" name="thumbnail_ext_url_nonce" value="'
	         . esc_attr( $nonce ) . '">';
	$html .= '<div><p>' . __('Or', 'txtdomain') . '</p>';
	$html .= '<p>' . __( 'Enter the url for external image', 'txtdomain' ) . '</p>';
	$html .= '<p><input type="url" name="thumbnail_ext_url" value="' . $value . '"></p>';

	//display an external image if exists
	if ( ! empty($value) && url_is_image( $value ) ) {
		$html .= '<p><img style="max-width:150px;height:auto;" src="'
		         . esc_url($value) . '"></p>';
		$html .= '<p>' . __( 'Leave url blank to remove.') . '</p>';
	}
	$html .= '</div>';
	return $html;
}

/**
 * Save external image URL value in post meta
 *
 * @param int $pid post ID
 * @param WP_Post $post post object
 *
 * @since 0.1
 */
function thumbnail_url_field_save( $pid, $post ) {

	//check if user can modify post or page, of post type supports featured images
	//and if autosave is already set. If fits anything, stop function
	$cap = $post->post_type === 'part' ? 'edit_page' : 'edit_post';
	if (
		! current_user_can( $cap, $pid )
		|| ! post_type_supports( $post->post_type, 'thumbnail' )
		|| defined( 'DOING_AUTOSAVE' )
	) {
		return;
	}

	//nonce verification and URL check
	$action = 'thumbnail_ext_url_' . $pid . get_current_blog_id();
	$nonce = filter_input( INPUT_POST, 'thumbnail_ext_url_nonce', FILTER_SANITIZE_STRING );
	$url = filter_input( INPUT_POST,  'thumbnail_ext_url', FILTER_VALIDATE_URL );
	if (
		empty( $nonce )
		|| ! wp_verify_nonce( $nonce, $action )
		|| ( ! empty( $url ) && ! url_is_image( $url ) )
	) {
		return;
	}

	//> save featured image information in metadata
	//if URL is provided set URL meta => if post did not have featured image linked,
	// set it with value 'by_url'
	if ( ! empty( $url ) ) {
		update_post_meta( $pid, '_thumbnail_ext_url', esc_url($url) );
		if ( ! get_post_meta( $pid, '_thumbnail_id', TRUE ) ) {
			update_post_meta( $pid, '_thumbnail_id', 'by_url' );
		}
	} elseif ( get_post_meta( $pid, '_thumbnail_ext_url', TRUE ) ) {
		//if URL field is empty, delete its meta and delete post link to featured image if
		// it was external
		delete_post_meta( $pid, '_thumbnail_ext_url' );
		if ( get_post_meta( $pid, '_thumbnail_id', TRUE ) === 'by_url' ) {
			delete_post_meta( $pid, '_thumbnail_id' );
		}
	}
	//<
}

/**
 * Create an HTML image container for external featured image
 *
 * @param string $html post HTML code
 * @param int $post_id post ID
 *
 * @return string updated HTML code of post
 *
 * @since 0.1
 */
function thumbnail_external_replace( $html, $post_id ) {

	//declare global variable post
	global $post;

	//if no external image applied, exit
	$url =  get_post_meta( $post_id, '_thumbnail_ext_url', TRUE );
	if ( empty( $url ) || ! url_is_image( $url ) ) {
		return $html;
	}

	//generate <img> container with parameters
	$alt = get_post_field( 'post_title', $post_id ) . ' ' .  __( 'thumbnail', 'txtdomain' );
	$attr = array( 'alt' => $alt );
	$attr = apply_filters( 'wp_get_attachment_image_attributes', $attr, $post, 'thumbnail' );
	$attr = array_map( 'esc_attr', $attr );
	$html = sprintf( '<img src="%s"', esc_url($url) );
	foreach ( $attr as $name => $value ) {
		$html .= " $name=" . '"' . $value . '"';
	}
	$html .= ' />';

	//return updated HTML code
	return $html;
}

/**
 * Add support of featured images to PressBooks post types
 */
function add_thumbnail_support () {
	add_post_type_support( 'chapter', 'thumbnail' );
	add_post_type_support( 'part', 'thumbnail' );
	add_post_type_support( 'front-matter', 'thumbnail' );
	add_post_type_support( 'back-matter', 'thumbnail' );
}

//initiate featured images support
add_action('init', 'add_thumbnail_support');

//> Hook all thumbnail related activities with external image related functions
add_filter( 'admin_post_thumbnail_html', 'thumbnail_url_field' );

add_action( 'save_post', 'thumbnail_url_field_save', 10, 2 );

add_filter( 'post_thumbnail_html', 'thumbnail_external_replace', 10, PHP_INT_MAX );
//<

/****** Add Thumbnails in Manage Posts/Pages List ******/
if ( !function_exists('AddThumbColumn')) {

	/**
	 * Adding featured image column to administration area
	 *
	 * @param array $cols existing columns
	 *
	 * @return mixed updated columns
	 *
	 * @since 0.1
	 */
	function AddThumbColumn( $cols ) {

		$cols['thumbnail'] = __( 'Thumbnail' );

		return $cols;
	}
}

/**
 * Set values of featured images columns in admin area
 *
 * @param string $column_name column name
 * @param int $post_id post ID
 *
 * @since 0.1
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
add_image_size( 'featured-narrow', 540 );
add_image_size( 'featured-standard', 720 );
add_image_size( 'featured-wide', 864 );

/*
* Auto update from github
*
* @since 0.1
*/
require 'vendor/plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://github.com/my-language-skills/pressbooks-featured-image/',
    __FILE__,
    'pressbooks-featured-image'
);