<?php
/**
 * Title
 *
 * タイトル関連の設定ファイル.
 *
 * @package WordPress
 * @subpackage Template
 */

/**
 * タイトルタグ出力
 *
 * @return void
 */
function output_title() {
	add_theme_support( 'title-tag' );
}

add_action( 'after_setup_theme', 'output_title' );

/**
 * タイトルタグの区切り文字の変更
 *
 * @param string $separator セパレータ.
 * @return string
 */
function rewrite_separator( $separator ) {
	$separator = '|';
	return $separator;
}

add_filter( 'document_title_separator', 'rewrite_separator' );
