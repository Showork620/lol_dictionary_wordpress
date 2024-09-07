<?php
/**
 * 投稿を全てリセットするための関数
 *
 * @return void
 */
function delete_all_items_posts() {
	// カスタム投稿タイプ 'custom_item' の全ての投稿を取得
	$custom_posts = get_posts(array(
		'post_type' => 'items', // カスタム投稿タイプのスラッグ
		'numberposts' => -1, // 全ての投稿を取得
		'post_status' => 'any', // 全てのステータスの投稿を取得
	));

	// 取得した投稿を削除
	foreach ($custom_posts as $post) {
		wp_delete_post($post->ID, true); // 第二引数を true にするとゴミ箱を経由せず完全に削除
	}
}
delete_all_items_posts();
echo "全てのアイテム投稿が削除されました。\n";