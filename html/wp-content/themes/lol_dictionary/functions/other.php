<?php
/**
 * Other
 *
 * その他の設定ファイル.
 *
 * @package WordPress
 * @subpackage Template
 */

/**
 * WordPressのファビコン設定無効化
 *
 * @return void
 */
function wp_favicon_delete() {
	exit;
}

add_action( 'do_faviconico', 'wp_favicon_delete' );

/**
 * Windows chrome で見えるL SEPを表示時に削除する
 *
 * @param string $contents contents.
 * @return string
 */
function usort_edit_lsep_contents( $contents ) {
	return hex2bin( str_replace( 'e280a8', '', bin2hex( $contents ) ) );
}

add_filter( 'the_content', 'usort_edit_lsep_contents' );

/**
 * アイキャッチ画像の有効化
 */
add_theme_support( 'post-thumbnails' );
