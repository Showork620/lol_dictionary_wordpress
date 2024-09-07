<?php
/**
 *  カスタム投稿タイプ "items" の init
 *
 * @return void
 */

function create_custom_post_types() {
	// Nomal Items カスタム投稿タイプ
	register_post_type(
		'items',
		array(
			'labels' => array(
				'name' => __('アイテム一覧'),
				'singular_name' => __('アイテム')
			),
			'public' => true,
			'has_archive' => true,
			'supports' => array('title', 'editor', 'custom-fields', 'thumbnail'),
			'taxonomies' => array('post_tag', 'tag', 'role'),
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
		add_meta_box('item_fields', 'アイテム投稿フィールド', 'item_fields_callback', 'items', 'normal', 'high');
	});

	function item_fields_callback($post) {
		$fields = array('id', 'colloq', 'from', 'into', 'gold', 'aram_detail');

		echo '<ul>';
		foreach ($fields as $field) {
			$value = get_post_meta($post->ID, $field, true);
			echo '<li style="list-style: none">';
			echo '<label for="' . $field . '">' . ucfirst($field) . '</label>';
			echo '<input type="text" id="' . $field . '" name="' . $field . '" value="' . esc_attr($value) . '" size="25" />';
			echo '</li>';
		}
		echo '</ul>';
	}

	add_action('save_post', function($post_id) {
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
		if (!current_user_can('edit_post', $post_id)) return;

		$fields = array('id', 'colloq', 'from', 'into', 'gold', 'aram_detail');
		foreach ($fields as $field) {
			if (isset($_POST[$field])) {
				update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
			}
		}
	});
}
add_action('init', 'register_custom_fields');

/**
 * 投稿一覧の並び順を名前順に変更
 *
 * @return void
 */
function set_custom_post_order($query) {
	if (!is_admin() || !$query->is_main_query()) {
		return;
	}

	if ($query->get('post_type') === 'items') {
		$query->set('orderby', 'title');
		$query->set('order', 'ASC');
	}
}
add_action('pre_get_posts', 'set_custom_post_order');


function flush_rewrite_rules_on_activation() {
    create_custom_post_types();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'flush_rewrite_rules_on_activation');