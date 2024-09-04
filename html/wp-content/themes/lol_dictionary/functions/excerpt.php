<?php
/**
 * Excerpt
 *
 * 抜粋の設定ファイル.
 *
 * @package WordPress
 * @subpackage Template
 */

/**
 * 抜粋の文字数を指定
 *
 * @param int $length 長さ.
 * @return int
 */
function custom_excerpt_length( $length ) {
	return 140;
}

add_filter( 'excerpt_length', 'custom_excerpt_length' );

/**
 * 抜粋の文末文字を指定
 *
 * @param string $more 文末文字.
 * @return string
 */
function custom_excerpt_more( $more ) {
	return ' ... ';
}

add_filter( 'excerpt_more', 'custom_excerpt_more' );
