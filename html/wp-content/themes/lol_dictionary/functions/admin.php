<?php
/**
 * Admin
 *
 * 管理画面系の設定ファイル.
 *
 * @package WordPress
 * @subpackage Template
 */

/**
 * Adminbarの非表示
 *
 * @return bool
 */
function disable_admin_bar() {
	return false;
}

add_filter( 'show_admin_bar', 'disable_admin_bar' );
