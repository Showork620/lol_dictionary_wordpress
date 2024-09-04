<?php
/**
 * Template functions and definitions
 *
 * @package WordPress
 * @subpackage Template
 */

get_template_part( 'functions/head' );

get_template_part( 'functions/include' );

get_template_part( 'functions/excerpt' );

get_template_part( 'functions/title' );

get_template_part( 'functions/admin' );

get_template_part( 'functions/query' );

get_template_part( 'functions/other' );

get_template_part( 'functions/utils' );

/**
 *  カスタム投稿を追加
 *
 * @return void
 */
function create_custom_post_type() {
    register_post_type('custom_item',
        array(
            'labels' => array(
                'name' => __('Custom Items'),
                'singular_name' => __('Custom Item')
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'custom-fields'),
        )
    );
}
add_action('init', 'create_custom_post_type');

function register_custom_fields() {
    add_action('add_meta_boxes', function() {
        add_meta_box('custom_fields', 'Custom Fields', 'custom_fields_callback', 'custom_item', 'normal', 'high');
    });

    function custom_fields_callback($post) {
        $fields = array('colloq', 'into', 'image', 'gold', 'tags', 'maps', 'stats');
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

        $fields = array('colloq', 'into', 'image', 'gold', 'tags', 'maps', 'stats');
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
            }
        }
    });
}
add_action('init', 'register_custom_fields');

function create_custom_posts_from_json() {
    // JSONデータを読み込む
    $json_data = '{
        "1001": {
            "name": "ブーツ",
            "description": "<mainText><stats>移動速度<attention>25</attention></stats><br><br></mainText>",
            "colloq": ";Boots of Speed;くつ;クツ;ぶーつ;ブーツ;靴;bu-tsu;bu-tu;butsu;butu",
            "into": ["3005", "3047", "3006", "3009", "3010", "3020", "3111", "3117", "3158"],
            "image": "1001.png",
            "gold": 300,
            "tags": ["Boots"],
            "maps": {"11": true, "12": true, "21": true, "22": false, "30": false, "33": false},
            "stats": {"FlatMovementSpeedMod": 25}
        },
        "1004": {
            "name": "フェアリー チャーム",
            "description": "<mainText><stats>基本マナ自動回復<attention>50%</attention></stats><br><br></mainText>",
            "colloq": ";Faerie Charm;ふぇありーちゃーむ;ようせい;フェアリー チャーム;feari-cha-mu;feari-tya-mu;fuearichamu;huearichamu",
            "into": ["3114", "4642", "3012"],
            "image": "1004.png",
            "gold": 250,
            "tags": ["ManaRegen"],
            "maps": {"11": true, "12": true, "21": true, "22": false, "30": false, "33": false},
            "stats": {}
        },
        "1006": {
            "name": "再生の珠",
            "description": "<mainText><stats>基本体力自動回復<attention>100%</attention></stats><br><br></mainText>",
            "colloq": ";Rejuvenation Bead;さいせいのたま;サイセイノタマ;みどり;ミドリ;再生の珠;saiseinotama",
            "into": ["3109", "3211", "3801"],
            "image": "1006.png",
            "gold": 300,
            "tags": ["HealthRegen"],
            "maps": {"11": true, "12": true, "21": true, "22": false, "30": false, "33": false},
            "stats": {}
        }
    }';

    $items = json_decode($json_data, true);

    foreach ($items as $item_id => $item) {
        // カスタム投稿を作成
        $post_id = wp_insert_post(array(
            'post_title' => $item['name'],
            'post_content' => $item['description'],
            'post_type' => 'custom_item',
            'post_status' => 'publish',
            'meta_input' => array(
                'colloq' => $item['colloq'],
                'into' => implode(', ', $item['into']),
                'image' => $item['image'],
                'gold' => $item['gold'],
                'tags' => implode(', ', $item['tags']),
                'maps' => json_encode($item['maps']),
                'stats' => json_encode($item['stats']),
            ),
        ));
    }
}

// この関数を適切なフックで呼び出します。例えば、管理画面での操作時に実行する場合：
add_action('admin_init', 'create_custom_posts_from_json');