<?php
/**
 * Utils
 *
 * 共通処理の管理ファイル.
 *
 * @package WordPress
 * @subpackage Template
 */

/**
 * 画像パス取得
 *
 * @param string $path Image path.
 * @return string
 */
function get_image_path( $path ) {
	return get_stylesheet_directory_uri() . '/assets/img' . $path;
}

/**
 * 数字から曜日に変換
 *
 * @param int $week_number Week number.
 * @return string
 */
function week_to_ja( $week_number ) {
	/**
	 * 日：0
	 * 月：1
	 * 火：2
	 * 水：3
	 * 木：4
	 * 金：5
	 * 土：6
	 */
	$week = array(
		'日',
		'月',
		'火',
		'水',
		'木',
		'金',
		'土',
	);

	return $week[ $week_number ];
}

/**
 * 本日かの判定
 *
 * @param string $date DateTime.
 * @return boolean
 */
function is_today( $date ) {
	return strtotime( gmdate( 'Y-m-d', strtotime( $date ) ) ) === strtotime( gmdate( 'Y-m-d' ) );
}

/**
 * SP判定
 *
 * @return boolean
 */
function is_mobile() {
	/**
	 * ユーザーエージェント種類
	 *  iPhone：iPhone
	 *  iPod：iPod touch
	 *  Android.*Mobile：1.5+ Android *** Only mobile
	 *  Windows.*Phone：*** Windows Phone
	 *  dream：Pre 1.5 Android
	 *  CUPCAKE：1.5+ Android
	 *  blackberry9500：Storm
	 *  blackberry9530：Storm
	 *  blackberry9520：Storm v2
	 *  blackberry9550：Storm v2
	 *  blackberry9800：Torch
	 *  webOS：Palm Pre Experimental
	 *  incognito：Other iPhone browser
	 *  webmate：Other iPhone browser
	 */
	$user_agents = array(
		'iPhone',
		'iPod',
		'Android.*Mobile',
		'Windows.*Phone',
		'dream',
		'CUPCAKE',
		'blackberry9500',
		'blackberry9530',
		'blackberry9520',
		'blackberry9550',
		'blackberry9800',
		'webOS',
		'incognito',
		'webmate',
	);
	$pattern     = '/' . implode( '|', $user_agents ) . '/i';

	if ( empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
		return false;
	}

	$user_agent = esc_url_raw( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) );
	return preg_match( $pattern, $user_agent );
}
