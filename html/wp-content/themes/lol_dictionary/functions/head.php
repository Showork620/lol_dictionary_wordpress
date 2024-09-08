<?php
/**
 * Head
 *
 * Headの設定ファイル.
 *
 * @package WordPress
 * @subpackage Template
 */

/**
 * Headの不要タグの削除
 */

/**
 * RSSフィード
 */
remove_action( 'wp_head', 'feed_links', 2 );

/**
 * RSSフィード
 */
remove_action( 'wp_head', 'feed_links_extra', 3 );

/**
 * Really Simple Discovery
 */
remove_action( 'wp_head', 'rsd_link' );

/**
 * Windows Live Writer
 */
remove_action( 'wp_head', 'wlwmanifest_link' );

/**
 * Indexへのリンク
 */
remove_action( 'wp_head', 'index_rel_link' );

/**
 * 分割ページへのリンク
 */
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );

/**
 * 分割ページへのリンク
 */
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );

/**
 * 前後のページへのリンク
 */
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );

/**
 * WordPressのバージョン
 */
remove_action( 'wp_head', 'wp_generator' );

/**
 * Embed対応
 */
remove_action( 'wp_head', 'rest_output_link_wp_head' );

/**
 * 絵文字対応
 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );

/**
 * 絵文字対応
 */
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );

/**
 * 絵文字対応
 */
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/**
 * 絵文字対応
 */
remove_action( 'admin_print_styles', 'print_emoji_styles' );
