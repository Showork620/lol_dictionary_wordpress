<?php
 /**
 * カスタム投稿のアイキャッチ画像を設定
 *
 * @return void
 */
function set_custom_post_thumbnail($post_id) {

	// カスタムフィールド「id」の値を取得
	$item_id = get_post_meta($post_id, 'id', true);

	// メディアライブラリからファイル名が一致する画像を検索
	$filename = $item_id . '.png';
	$args = array(
		'post_type' => 'attachment',
		'meta_query' => array(
			array(
				'key' => '_wp_attached_file',
				'value' => $filename,
				'compare' => 'LIKE'
			)
		)
	);
	$attachments = get_posts($args);

	if ($attachments) {
		// 最初の一致する画像をアイキャッチ画像として設定
		$attachment_id = $attachments[0]->ID;
		set_post_thumbnail($post_id, $attachment_id);
	}
}

/**
 *  Items カスタム投稿タイプの出力
 *
 * @return void
 */
function create_custom_posts_from_json() {

	// JSONデータを読み込む
	$item_data_path = get_template_directory() . '/assets/json/item_data.json';
	$item_detail_path = get_template_directory() . '/assets/json/item_detail.json';

	// アイテムデータ
	$items = null;
	$details = null;

	if (file_exists($item_data_path)) {
		$json_data = file_get_contents($item_data_path);
		$items = json_decode($json_data, true);
	} else {
		// ファイルが存在しない場合の処理
		echo 'Error: item_data_json file does not exist.';
		return;
	}

	if (file_exists($item_detail_path)) {
		$json_data = file_get_contents($item_detail_path);
		$details = json_decode($json_data, true);
	} else {
		// ファイルが存在しない場合の処理
		echo 'Error: item_detail_json file does not exist.';
		return;
	}

	if (is_array($items)) {
		foreach ($items as $item_id => $item) {
			// 投稿が既に存在するかチェック
			$existing_post = get_posts(array(
				'post_type' => 'items',
				'meta_query' => array(
					array(
						'key' => 'id',
						'value' => isset($item['id']) ? $item['id'] : '',
						'compare' => '='
					)
				)
			));

			// 投稿が存在しない場合にのみ新しい投稿を作成
			if (empty($existing_post)) {

				// normal_detail があれば description を上書き
				$contents = isset($details[$item['id']]['normal']) ? $details[$item['id']]['normal'] : $item['description'];

				$post_id = wp_insert_post(array(
					'post_title' => isset($item['name']) ? $item['name'] : '',
					'post_content' => isset($item['description']) ? $item['description'] : '',
					'post_type' => 'items',
					'post_status' => 'publish',
					'meta_input' => array(
						'id' => isset($item['id']) ? $item['id'] : '',
						'gold' => isset($item['gold']) ? $item['gold'] : '',
						'from' => isset($item['from']) ? implode(', ', $item['from']) : '',
						'into' => isset($item['into']) ? implode(', ', $item['into']) : '',
						'specialRecipe' => isset($item['specialRecipe']) ? $item['specialRecipe'] : '',
						'destination' => isset($item['destination']) ? $item['destination'] : '',
						'nomal_item' => isset($item['nomal_item']) ? $item['nomal_item'] : '',
						'aram_item' => isset($item['aram_item']) ? $item['aram_item'] : '',
						'colloq' => isset($item['colloq']) ? $item['colloq'] : '',
						'aram_detail' => isset($details[$item['id']]['aram']) ? $details[$item['id']]['aram'] : '',
					),
				));

				// タグを設定
				if (isset($item['tags']) && is_array($item['tags'])) {
					wp_set_post_terms($post_id, $item['tags'], 'post_tag');
				}

				// roleを設定
				if (isset($item['role']) && is_array($item['role'])) {
					wp_set_post_terms($post_id, $item['role'], 'role');
				}

				// アイキャッチ画像を設定
				set_custom_post_thumbnail($post_id);
			}
		}
	} else {
		// JSONデータが正しくデコードされなかった場合の処理
		echo 'Error: JSON data could not be decoded.';
	}
}
create_custom_posts_from_json();
echo 'items 投稿を出力しました: ' . date('Y-m-d H:i:s');
