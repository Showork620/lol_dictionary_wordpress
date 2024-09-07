<?php
/**
 *  カスタム投稿タイプの作成
 *
 * @return void
 */
return;

function create_custom_post_types() {
	// Nomal Items カスタム投稿タイプ
	register_post_type('nomal_item',
		array(
			'labels' => array(
				'name' => __('Nomral Items'),
				'singular_name' => __('Nomral Item')
			),
			'public' => true,
			'has_archive' => true,
			'supports' => array('title', 'editor', 'custom-fields', 'thumbnail'),
			'taxonomies' => array('post_tag'),
		)
	);

	// Aram Items カスタム投稿タイプ
	register_post_type('aram_item',
		array(
			'labels' => array(
				'name' => __('Aram Items'),
				'singular_name' => __('Aram Item')
			),
			'public' => true,
			'has_archive' => true,
			'supports' => array('title', 'editor', 'custom-fields', 'thumbnail'),
			'taxonomies' => array('post_tag'),
		)
	);
}
add_action('init', 'create_custom_post_types');


/**
 *  Items 投稿タイプにカスタムフィールドを追加
 *
 * @return void
 */
function register_custom_fields() {
	add_action('add_meta_boxes', function() {
		add_meta_box('custom_fields', 'Custom Fields', 'custom_fields_callback', array('nomal_item', 'aram_item'), 'normal', 'high');
	});

	function custom_fields_callback($post) {
		$fields = array('id', 'colloq', 'from', 'into', 'gold');
		foreach ($fields as $field) {
			$value = get_post_meta($post->ID, $field, true);
			echo '<label for="' . $field . '">' . ucfirst($field) . '</label>';
			echo '<input type="text" id="' . $field . '" name="' . $field . '" value="' . esc_attr($value) . '" size="25" />';
			echo '<br>';
		}
	}

	add_action('save_post', function($post_id) {
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
		if (!current_user_can('edit_post', $post_id)) return;

		$fields = array('id', 'colloq', 'from', 'into', 'gold');
		foreach ($fields as $field) {
			if (isset($_POST[$field])) {
				update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
			}
		}
	});
}
add_action('init', 'register_custom_fields');


/**
 * JSONファイルからカスタム投稿を自動作成
 *
 * @return void
 */
function create_custom_posts_from_json($json_file, $post_type) {

	// JSONデータを読み込む
	$path = get_template_directory() . '/assets/json/' . $json_file;

	if (file_exists($path)) {
		$json_data = file_get_contents($path);
		$items = json_decode($json_data, true);

		if (is_array($items)) {
			foreach ($items as $item_id => $item) {
				// 投稿が既に存在するかチェック
				$existing_post = get_posts(array(
					'post_type' => $post_type,
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
					$post_id = wp_insert_post(array(
						'post_title' => isset($item['name']) ? $item['name'] : '',
						'post_content' => isset($item['description']) ? $item['description'] : '',
						'post_type' => $post_type,
						'post_status' => 'publish',
						'meta_input' => array(
							'id' => isset($item['id']) ? $item['id'] : '',
							'colloq' => isset($item['colloq']) ? $item['colloq'] : '',
							'from' => isset($item['from']) ? implode(', ', $item['from']) : '',
							'into' => isset($item['into']) ? implode(', ', $item['into']) : '',
							'gold' => isset($item['gold']) ? $item['gold'] : '',
							'has_detail' => false,
						),
					));

					// タグを設定
					if (isset($item['tags']) && is_array($item['tags'])) {
						wp_set_post_terms($post_id, $item['tags'], 'post_tag');
					}
				}
			}
		} else {
			// JSONデータが正しくデコードされなかった場合の処理
			echo 'Error: JSON data could not be decoded.';
		}
	} else {
		// ファイルが存在しない場合の処理
		echo 'Error: JSON file does not exist.';
	}
}

/**
 * カスタム投稿を作成（json名と投稿タイプを指定）
 *
 * @return void
 */
// Nomal Items のカスタム投稿を作成
add_action('init', function() {
	create_custom_posts_from_json('nomal_items.json', 'nomal_item');
});

// Aram Items のカスタム投稿を作成
add_action('init', function() {
	create_custom_posts_from_json('aram_items.json', 'aram_item');
});


/**
 * カスタム投稿の一覧に「Needs Editing」カラムを追加
 *
 * @return void
 */
function add_has_detail_column($columns) {
	$columns['has_detail'] = 'Needs Editing';
	return $columns;
}
add_filter('manage_nomal_item_posts_columns', 'add_has_detail_column');
add_filter('manage_aram_item_posts_columns', 'add_has_detail_column');

// カラムに値を表示
function show_has_detail_column($column, $post_id) {
	if ($column === 'has_detail') {
			$has_detail = get_post_meta($post_id, 'has_detail', true);
			echo $has_detail ? 'Yes' : 'No';
	}
}
add_action('manage_nomal_item_posts_custom_column', 'show_has_detail_column', 10, 2);
add_action('manage_aram_item_posts_custom_column', 'show_has_detail_column', 10, 2);

// フィルターのドロップダウンを追加
function add_has_detail_filter() {
	$screen = get_current_screen();
	if ($screen->post_type === 'nomal_item' || $screen->post_type === 'aram_item') {
			$value = isset($_GET['has_detail_filter']) ? $_GET['has_detail_filter'] : '';
			echo '<select name="has_detail_filter">';
			echo '<option value="">All Needs Editing</option>';
			echo '<option value="1"' . selected($value, '1', false) . '>Yes</option>';
			echo '<option value="0"' . selected($value, '0', false) . '>No</option>';
			echo '</select>';
	}
}
add_action('restrict_manage_posts', 'add_has_detail_filter');

// フィルターのクエリを変更
function filter_has_detail_query($query) {
	global $pagenow;
	$post_type = isset($_GET['post_type']) ? $_GET['post_type'] : '';
	if ($pagenow === 'edit.php' && ($post_type === 'nomal_item' || $post_type === 'aram_item') && isset($_GET['has_detail_filter']) && $_GET['has_detail_filter'] !== '') {
			$meta_query = array(
					array(
							'key' => 'has_detail',
							'value' => $_GET['has_detail_filter'],
							'compare' => '='
					)
			);
			$query->set('meta_query', $meta_query);
	}
}
add_action('pre_get_posts', 'filter_has_detail_query');


/**
 * カスタム投稿のアイキャッチ画像を設定
 *
 * @return void
 */
function set_custom_post_thumbnail($post_id) {
	// 投稿タイプをチェック（必要に応じてカスタム投稿タイプを指定）
	$post_type = get_post_type($post_id);
	if ($post_type !== 'nomal_item' && $post_type !== 'aram_item') {
		return;
	}

	// カスタムフィールド「id」の値を取得
	$custom_id = get_post_meta($post_id, 'id', true);
	if (!$custom_id) {
		return;
	}

	// メディアライブラリからファイル名が一致する画像を検索
	$filename = $custom_id . '.png';
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
add_action('save_post', 'set_custom_post_thumbnail');


/**
 * 投稿一覧の並び順を名前順に変更
 *
 * @return void
 */
function set_custom_post_order($query) {
	if (!is_admin() || !$query->is_main_query()) {
		return;
	}

	if ($query->get('post_type') === 'nomal_item' || $query->get('post_type') === 'aram_item') {
		$query->set('orderby', 'title');
		$query->set('order', 'ASC');
	}
}
add_action('pre_get_posts', 'set_custom_post_order');


/**
 * 投稿を全てリセットするための関数
 *
 * @return void
 */
function delete_all_custom_posts() {
	// カスタム投稿タイプ 'custom_item' の全ての投稿を取得
	$custom_posts = get_posts(array(
		'post_type' => 'aram_item', // カスタム投稿タイプのスラッグ
		'numberposts' => -1, // 全ての投稿を取得
		'post_status' => 'any', // 全てのステータスの投稿を取得
	));

	// 取得した投稿を削除
	foreach ($custom_posts as $post) {
		wp_delete_post($post->ID, true); // 第二引数を true にするとゴミ箱を経由せず完全に削除
	}
}
// add_action('admin_init', 'delete_all_custom_posts');
