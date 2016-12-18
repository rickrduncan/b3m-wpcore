<?php
/**
 * 	Plugin Name:	B3M WP Core
 * 	Description:	WordPress core customizations.
 * 	Author:			Rick R. Duncan - B3Marketing, LLC
 * 	Author URI:		http://rickrduncan.com
 *
 *
 * 	Version:		1.0.0
 * 	License:		GPLv3
 * License URI:		https://www.gnu.org/licenses/gpl-2.0.html
 *
 *
 */
 

/**
* Remove 'Editor' from 'Appearance' Menu. 
* This stops users from being able to edit files from within WordPress. 
*
* @since 1.0.0
*/
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
	define( 'DISALLOW_FILE_EDIT', true );
}


/**
* Add the ability to use shortcodes in widgets
*
* @since 1.0.0
*/
add_filter( 'widget_text', 'do_shortcode' ); 


/**
* Prevent WordPress from compressing images
*
* @since 1.0.0
*/
add_filter( 'jpeg_quality', create_function( '', 'return 100;' ) );


/**
* Remove silly-ass emoji code
*
* Source code credit: http://ottopress.com/
*
* @since 1.0.0
*/
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );   
remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );     
remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );


/**
* Remove items from the <head> section
*
* @since 1.0.0
*/
remove_action( 'wp_head', 'wp_generator' );								//* Remove WP Version number
remove_action( 'wp_head', 'wlwmanifest_link' );							//* Remove wlwmanifest_link
remove_action( 'wp_head', 'rsd_link' );									//* Remove rsd_link
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );				//* Remove shortlink
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );	//* Remove previous/next post links


/**
* Limit the number of post revisions
*
* @since 1.0.0
*/
function b3m_set_revision_max( $num, $post ) {     
    $num = 5; //change 5 to match your preferred number of revisions to keep
    return $num; 
}
add_filter( 'wp_revisions_to_keep', 'b3m_set_revision_max', 10, 2 );


/**
* Add Colophon meta tag into head of document
*
* @since 1.0.0
*/
function b3m_insert_head_meta_data() { 
	echo '<meta name="web_author" content="Rick R. Duncan — rickrduncan.com" />';
}
add_action( 'wp_head', 'b3m_insert_head_meta_data' );