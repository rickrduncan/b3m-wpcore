<?php
/**
* Plugin Name:		B3M WP Core
* Description:		WordPress core customizations.
* Author:			Rick R. Duncan - B3Marketing, LLC
* Author URI:		http://rickrduncan.com
*
* License:			GPLv3
* License URI:		https://www.gnu.org/licenses/gpl-2.0.html
*
* Version:			1.0.0
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


/**
* Change WordPress welcome message from 'Howdy'
*
* @since 1.0.0
*/
function b3m_change_howdy( $translated, $text, $domain ) {

    if ( !is_admin() || 'default' != $domain )
        return $translated;

    if ( false !== strpos( $translated, 'Howdy' ) )
        return str_replace( 'Howdy', 'Welcome', $translated );

    return $translated;
}
add_filter( 'gettext', 'b3m_change_howdy', 10, 3 );


/**
* Remove clutter from main dasboard screen 
*
* @since 1.0.0
*/
function b3m_remove_dashboard_widgets() {
	
	//remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); 		// right now
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal'); 	// recent comments
    remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal'); 	// incoming links
    remove_meta_box('dashboard_plugins', 'dashboard', 'normal'); 			// plugins
    remove_meta_box('dashboard_quick_press', 'dashboard', 'normal'); 		// quick press
    remove_meta_box('dashboard_recent_drafts', 'dashboard', 'normal'); 		// recent drafts
    remove_meta_box('dashboard_primary', 'dashboard', 'normal'); 			// wordpress blog
    remove_meta_box('dashboard_secondary', 'dashboard', 'normal'); 			// other wordpress news
}
add_action( 'admin_init', 'b3m_remove_dashboard_widgets' );


/**
* Remove unwanted core widgets
*
* @since 1.0.0
*/
function b3m_remove_default_widgets() {
	//unregister_widget('WP_Widget_Pages');
    //unregister_widget('WP_Widget_Search');
    //unregister_widget('WP_Widget_Text');
    //unregister_widget('WP_Widget_Categories');
    //unregister_widget('WP_Widget_Recent_Posts');
    //unregister_widget('WP_Nav_Menu_Widget');
    unregister_widget('WP_Widget_Calendar');
    unregister_widget('WP_Widget_Archives');
    unregister_widget('WP_Widget_Links');
    unregister_widget('WP_Widget_Meta');
    unregister_widget('WP_Widget_Recent_Comments');
    unregister_widget('WP_Widget_RSS');
    //unregister_widget('WP_Widget_Tag_Cloud');
    unregister_widget('Twenty_Eleven_Ephemera_Widget');
}
add_action( 'widgets_init', 'b3m_remove_default_widgets', 11 );



/**
* Don't Update Theme
* @author Mark Jaquith
* @link http://markjaquith.wordpress.com/2009/12/14/excluding-your-plugin-or-theme-from-update-checks/
*
* @since 1.0.0
*/
function b3m_dont_update_theme( $r, $url ) {
	
	if ( 0 !== strpos( $url, 'http://api.wordpress.org/themes/update-check' ) )
		return $r; // Not a theme update request. Bail immediately.
	$themes = unserialize( $r['body']['themes'] );
	unset( $themes[ get_option( 'template' ) ] );
	unset( $themes[ get_option( 'stylesheet' ) ] );
	$r['body']['themes'] = serialize( $themes );
	
	return $r;
}
add_filter( 'http_request_args', 'b3m_dont_update_theme', 5, 2 );
