<?php 
/**
 * 管理メニューから実行する処理
 *
 * @return void
 */

// アイテムデータ（item_data.json）更新
function add_custom_menu_page() {
	add_menu_page(
		'Update Item Data', // ページタイトル
		'アイテムデータ更新', // メニュータイトル
		'manage_options',   // 権限
		'update-item-data', // メニューのスラッグ
		'update_item_data_callback' // コールバック関数
	);
}
add_action('admin_menu', 'add_custom_menu_page');
function update_item_data_callback() {
	include get_template_directory() . '/functions/update_item_data.php';
}
