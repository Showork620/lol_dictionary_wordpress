<?php
/**
 * Include
 *
 * 外部ファイル読み込みの設定ファイル.
 *
 * @package WordPress
 * @subpackage Template
 */

/**
 * Google fontsの読み込み.
 *
 * @return void
 */
function google_font_scripts() {
	wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Noto+Sans+JP:wght@400;500;700|Roboto:wght@300;400;700&display=swap', array(), '1.0.0' );
}

add_action( 'wp_enqueue_scripts', 'google_font_scripts' );

/**
 * CSSの読み込み.
 *
 * @return void
 */
function add_styles() {
	wp_enqueue_style( 'style', get_stylesheet_directory_uri() . '/assets/css/style.css', array(), '1.0.0' );
}

add_action( 'wp_enqueue_scripts', 'add_styles' );

/**
 * JSの読み込み.
 *
 * @return void
 */
function add_scripts() {
	/**
	 * WordPress提供のjquery.jsを読み込まない
	 */
	wp_deregister_script( 'jquery' );
	wp_enqueue_script( 'js', get_stylesheet_directory_uri() . '/assets/js/main.js', array(), '1.0.0', true );
}

add_action( 'wp_enqueue_scripts', 'add_scripts' );
