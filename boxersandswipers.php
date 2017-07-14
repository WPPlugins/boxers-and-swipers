<?php
/*
Plugin Name: Boxers and Swipers
Plugin URI: https://wordpress.org/plugins/boxers-and-swipers/
Version: 3.02
Description: Integrates Colorbox, Slimbox, Nivo Lightbox, Image Lightbox, Photoswipe and Swipebox into WordPress.
Author: Katsushi Kawamori
Author URI: http://riverforest-wp.info/
Text Domain: boxers-and-swipers
Domain Path: /languages
*/

/*  Copyright (c) 2014- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; version 2 of the License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

	load_plugin_textdomain('boxers-and-swipers');
//	load_plugin_textdomain('boxers-and-swipers', false, basename( dirname( __FILE__ ) ) . '/languages' );

	define("BOXERSANDSWIPERS_PLUGIN_BASE_FILE", plugin_basename(__FILE__));
	define("BOXERSANDSWIPERS_PLUGIN_BASE_DIR", dirname(__FILE__));

	require_once( BOXERSANDSWIPERS_PLUGIN_BASE_DIR . '/req/BoxersAndSwipersRegist.php' );
	$boxersandswipersregistandheader = new BoxersAndSwipersRegist();
	add_action('admin_init', array($boxersandswipersregistandheader, 'register_settings'));
	unset($boxersandswipersregistandheader);

	require_once(BOXERSANDSWIPERS_PLUGIN_BASE_DIR . '/req/BoxersAndSwipersAdmin.php');
	$boxersandswipersadmin = new BoxersAndSwipersAdmin();
	add_action('admin_menu', array($boxersandswipersadmin, 'plugin_menu'));
	add_filter('plugin_action_links', array($boxersandswipersadmin, 'settings_link'), 10, 2 );
	add_action('admin_menu', array($boxersandswipersadmin, 'add_exclude_boxersandswipers_custom_box'));
	add_action('save_post', array($boxersandswipersadmin, 'save_exclude_boxersandswipers_postdata'));
	add_filter('manage_posts_columns', array($boxersandswipersadmin, 'posts_columns_boxersandswipers'));
	add_action('manage_posts_custom_column', array($boxersandswipersadmin, 'posts_custom_columns_boxersandswipers'), 10, 2);
	add_filter('manage_pages_columns', array($boxersandswipersadmin, 'pages_columns_boxersandswipers'));
	add_action('manage_pages_custom_column', array($boxersandswipersadmin, 'pages_custom_columns_boxersandswipers'), 10, 2);
	add_action('quick_edit_custom_box', array($boxersandswipersadmin, 'display_custom_quickedit_boxersandswipers'), 10, 2);
	add_action('admin_enqueue_scripts', array($boxersandswipersadmin, 'wp_boxersandswipers_admin_enqueue_scripts'));
	add_action('admin_enqueue_scripts', array($boxersandswipersadmin, 'load_custom_wp_admin_style'));
	unset($boxersandswipersadmin);

	include_once(BOXERSANDSWIPERS_PLUGIN_BASE_DIR.'/inc/BoxersAndSwipers.php');
	$boxersandswipers = new BoxersAndSwipers();
	$device = $boxersandswipers->agent_check();
	$boxersandswipers_effect = get_option('boxersandswipers_effect');
	$boxersandswipers->effect = $boxersandswipers_effect[$device];

	add_filter('the_content', array($boxersandswipers, 'add_anchor_tag'));
	add_filter('post_thumbnail_html', array($boxersandswipers, 'add_post_thumbnail_tag'), 10, 5);

	add_filter('post_infiniteallimages', array($boxersandswipers, 'add_anchor_tag'));

	add_action('wp_enqueue_scripts', array($boxersandswipers, 'load_frontend_scripts'));
	add_action('wp_enqueue_scripts', array($boxersandswipers, 'load_frontend_styles'));

	unset($boxersandswipers);

?>