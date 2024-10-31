<?php

/**
 * Plugin Name: Nicho 
 * Plugin URI: https://nicho.com/p/embed/wordpress
 * Description: Nicho makes it easy to deliver a seamless brand experience on any website to drive engagement and purchases with a single line of code. Our self-service platform enables brands to deliver the right social content at the right time, and control the experience from end-to-end on any device.
 * Version: 0.0.2
 * Author: SocioFabrica 
 * Author URI: http://sociofabrica.com/
 * License: GPL2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Add rewriting rule for gallery item
 */

function nicho_rewrite_rules() {
  $post_name = get_option('nicho_post_name');
  add_rewrite_rule($post_name . '/(.*)/(.*)', 'index.php?pagename=' . $post_name, 'top');
  add_rewrite_rule($post_name . '/(.*)', 'index.php?pagename=' . $post_name, 'top');
  flush_rewrite_rules();
}

/**
 * Admin Panel Modification
 * @see Settings > Nicho Plugin
 */

if ( is_admin() ){ // admin actions
  add_action( 'admin_menu', 'nicho_admin_actions' );
  add_action( 'admin_init', 'nicho_register_settings' );
}

function nicho_register_settings() {
  /**
   * @see http://codex.wordpress.org/Creating_Options_Pages
   */
  register_setting( 'nicho-settings-group', 'nicho_post_name', 'sanitize_text_field' );
  register_setting( 'nicho-settings-group', 'nicho_post_title', 'sanitize_text_field' );
  register_setting( 'nicho-settings-group', 'nicho_post_id', 'sanitize_text_field' );
  register_setting( 'nicho-settings-group', 'nicho_url', 'esc_url' );
  register_setting( 'nicho-settings-group', 'nicho_post_offset', 'sanitize_text_field' );
}

function nicho_admin_actions() {
  add_options_page('Nicho Plugin Settings', 'Nicho Settings', 'manage_options', 'nicho.options.php', 'nicho_options');
}

function nicho_options() {
  include('nicho.options.php');
}

add_action( 'edit_post', 'nicho_sync');
add_action( 'updated_option', 'nicho_updated_option' );

function nicho_updated_option( $option ) {
  $post_id = get_option( 'nicho_post_id' );

  if ($option === 'nicho_post_name') {
    $post = array(
      'ID'        => $post_id,
      'post_name' => get_option( 'nicho_post_name' ),
    );
    nicho_rewrite_rules();
  } else if ($option === 'nicho_post_title') {
    $post = array(
      'ID'         => $post_id,
      'post_title' => get_option( 'nicho_post_title' ),
    );
  }
  
  if ( $option === 'nicho_post_name' || $option === 'nicho_post_title' ) {
    wp_update_post( $post );
  }
}

function nicho_sync( $post_id ) {
  $post = get_post( $post_id );
  $nicho_post_id = get_option( 'nicho_post_id' );
  if ( $post_id == $nicho_post_id ) {
    update_option( 'nicho_post_name', $post->post_name );
    update_option( 'nicho_post_title', $post->post_title );
  }
}

/**
 * Add javascript files
 */

add_action( 'wp_enqueue_scripts', 'my_enqueued_assets' );

function my_enqueued_assets() {
	wp_enqueue_style( 'nicho-style', plugin_dir_url( __FILE__ ) . '/css/style.css', null, current_time( 'timestamp' ));
}

/**
 * Adding custom template for nicho
 */

add_filter( 'template_include', 'nicho_page_template');

function nicho_page_template( $template ) {
  $post_name = get_option('nicho_post_name');

	if ( is_page( $post_name ) ) {
    return plugin_dir_path( __FILE__ ) . '/templates/page-nicho.php';
	}

	return $template;
}

/**
 * Add filter to add custom nicho script attributes
 */
add_filter('script_loader_tag', 'nicho_add_script_attributes', 10, 2);

function nicho_add_script_attributes($tag, $handle) {
  if ( 'nicho' !== $handle ) {
    return $tag;
  }

  return str_replace( ' src', ' nicho-url="' . esc_attr(get_option('nicho_url')) . '" nicho-parent="#nicho-page" test="test" src', $tag );
}


/**
 * Activate/Deactivate/Uninstall hooks
 */

register_activation_hook(__FILE__, 'nicho_activation_hook');
register_uninstall_hook(__FILE__, 'nicho_uninstall_hook');
register_deactivation_hook(__FILE__, 'nicho_deactivation_hook');

function nicho_activation_hook() {
 /**
  * @see http://codex.wordpress.org/Function_Reference/wp_insert_post
  */
  $post = array(
    'post_content'   => '', 
    'post_name'      => 'nicho',
    'post_title'     => 'Nicho Gallery',
    'post_status'    => 'publish',
    'post_type'      => 'page',
    'post_author'    => 1,
    'comment_status' => 'closed',
    'ping_status' => 'closed'
  );

  $post_id = wp_insert_post( $post );
  
  update_option( 'nicho_post_name', $post['post_name'] );
  update_option( 'nicho_post_title', $post['post_title'] );
  update_option( 'nicho_post_offset', '0px' );
  update_option( 'nicho_post_id', $post_id );
  update_option( 'nicho_url', '//demo.nicho.com/' );
  nicho_rewrite_rules();
}

function nicho_deactivation_hook() {
  /**
   * @see http://codex.wordpress.org/Function_Reference/wp_delete_post
   */
  $force_delete = true;
  $post_id = get_option('nicho_post_id');
  wp_delete_post( $post_id, $force_delete );
  flush_rewrite_rules();
}

function nicho_uninstall_hook() {
  if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
    exit();

  $force_delete = true;
  $post_id = get_option('nicho_post_id');
  wp_delete_post( $post_id, $force_delete );
}
