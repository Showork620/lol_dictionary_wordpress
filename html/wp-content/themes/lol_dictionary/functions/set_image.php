<?php
/**
 * 画像の設定
 *
 * @return void
 */


//  // 最新バージョンを取得
//  function get_new_version() {
//     $version_url = "https://ddragon.leagueoflegends.com/api/versions.json";

//     // cURLを使用してAPIからデータを取得
//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, $version_url);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//     $response = curl_exec($ch);
//     curl_close($ch);

//     // JSONデータをデコード
//     $data = json_decode($response, true);

//     // 最新バージョンを返す
//     return $data[0];
// }

// $VERSION = get_new_version();
// $URL = "http://ddragon.leagueoflegends.com/cdn/" . $VERSION . "/img/item/";


// // JSONファイルからデータを取得
// $json_file_path = get_template_directory() . '/assets/json/all_items.json';
// $json_data = file_get_contents($json_file_path);
// $items = json_decode($json_data, true);


// // 画像をURLからアップロードし、添付ファイルIDと新規アップロードフラグを返す関数
// function upload_image_from_url($image_url) {
//     $upload_dir = wp_upload_dir();
//     $filename = basename($image_url);
//     $file_path = $upload_dir['path'] . '/' . $filename;

//     // 画像が既に存在するかチェック、画像名が一致するファイルがあればスキップする
//     $existing_attachment = get_posts(array(
//         'post_type' => 'attachment',
//         'meta_query' => array(
//             array(
//                 'key' => '_wp_attached_file',
//                 'value' => ltrim($upload_dir['subdir'] . '/' . $filename, '/'),
//                 'compare' => '='
//             )
//         )
//     ));

//     if ($existing_attachment) {
//         return array('id' => $existing_attachment[0]->ID, 'new' => false);
//     }

//     // 画像をアップロード
//     $image_data = file_get_contents($image_url);
//     if (wp_mkdir_p($upload_dir['path'])) {
//         $file = $upload_dir['path'] . '/' . $filename;
//     } else {
//         $file = $upload_dir['basedir'] . '/' . $filename;
//     }
//     file_put_contents($file, $image_data);

//     $wp_filetype = wp_check_filetype($filename, null);
//     $attachment = array(
//         'post_mime_type' => $wp_filetype['type'],
//         'post_title' => sanitize_file_name($filename),
//         'post_content' => '',
//         'post_status' => 'inherit'
//     );
//     $attach_id = wp_insert_attachment($attachment, $file);
//     require_once(ABSPATH . 'wp-admin/includes/image.php');
//     $attach_data = wp_generate_attachment_metadata($attach_id, $file);
//     wp_update_attachment_metadata($attach_id, $attach_data);

//     return array('id' => $attach_id, 'new' => true);
// }

// // 各アイテムの画像URLを生成し、画像をアップロード
// foreach ($items as $item) {
//     $imageUrl = $URL . $item['id'] . ".png";
//     $result = upload_image_from_url($imageUrl);
//     if ($result['new']) {
//         echo "Uploaded: " . $imageUrl . "\n"; // 新しくアップロードされた画像URLを表示
//     } else {
//         echo "Skipped: " . $imageUrl . "\n"; // スキップされた画像URLを表示
//     }
// }