<?php 
/**
 * 管理メニューから実行する処理
 *
 * @return void
 */

// アイテムデータ（item_data.json）更新.
function add_custom_menu_page() {
    // TODO: アイテム画像をセット
    // add_menu_page{};

    // アイテム画像をメディアライブラリに登録
    add_menu_page(
        'Set Item Images', // ページタイトル
        'item 画像をメディアに登録', // メニュータイトル
        'manage_options',   // 権限
        'set-item-images', // メニューのスラッグ
        'set_item_images_callback' // コールバック関数
    );

    // アイテムデータ更新
	add_menu_page(
		'Update Item Data', // ページタイトル
		'Item Data 更新', // メニュータイトル
		'manage_options',   // 権限
		'update-item-data', // メニューのスラッグ
		'update_item_data_callback' // コールバック関数
	);

    // アイテム投稿出力
    add_menu_page(
		'Output Item Post', // ページタイトル
		'Item Post 出力', // メニュータイトル
		'manage_options',   // 権限
		'output-item-post', // メニューのスラッグ
		'output_item_post_callback' // コールバック関数
	);

    // item_detail.json 出力
    // add_menu_page(
    //     'Output Item Detail Json', // ページタイトル
    //     'Item Detail Json 出力', // メニュータイトル
    //     'manage_options',   // 権限
    //     'output-item-detail-json', // メニューのスラッグ
    //     'output_item_detail_json_callback' // コールバック関数
    // );

    // item_post をリセット
    add_menu_page(
        'Reset Item Post', // ページタイトル
        'Items Post リセット', // メニュータイトル
        'manage_options',   // 権限
        'reset-item-post', // メニューのスラッグ
        'reset_item_post_callback' // コールバック関数
    );
}
add_action('admin_menu', 'add_custom_menu_page');

// TODO: アイテム画像をセット
// function set_item_images_callback() {}

function set_item_images_callback() {
    include get_template_directory() . '/functions/exection/set_item_images.php';
}
function update_item_data_callback() {
	include get_template_directory() . '/functions/exection/update_item_data.php';
}

// function output_item_detail_json_callback() {
//     include get_template_directory() . '/functions/exection/output_item_detail_json.php';
// }

function output_item_post_callback() {
    include get_template_directory() . '/functions/exection/output_item_post.php';
}

function reset_item_post_callback() {
    include get_template_directory() . '/functions/exection/reset_item_post.php';
}

