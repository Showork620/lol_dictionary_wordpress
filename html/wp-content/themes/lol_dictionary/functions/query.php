<?php
/**
 * Query
 *
 * 共通クエリの設定ファイル.
 *
 * @package WordPress
 * @subpackage Template
 */

/**
 * パスワード保護状態の投稿をメインクエリから除外
 *
 * @param WP_Query $query WP Query.
 * @return void
 */
function customize_main_query( $query ) {
	/**
	 * 管理画面以外 かつ メインクエリー
	 */
	if ( ! is_admin() && $query->is_main_query() ) {
		if ( $query->is_archive() ) {
			$query->set( 'has_password', false );
		}
	}
}

add_action( 'pre_get_posts', 'customize_main_query' );
