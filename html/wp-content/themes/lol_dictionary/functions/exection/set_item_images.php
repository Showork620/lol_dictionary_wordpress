<?php
/**
 * 画像をメディアライブラリに登録
 *
 * @param string $file_path 画像ファイルのパス
 * @param int $post_id 添付ファイルを関連付ける投稿 ID
 * @return int|WP_Error 添付ファイル ID または WP_Error オブジェクト
 */
function upload_image_to_media_library($file_path, $post_id = 0) {
    // ファイルの情報を取得
    $filetype = wp_check_filetype(basename($file_path), null);
    $wp_upload_dir = wp_upload_dir();

    // アップロードディレクトリに移動するファイルのパスを設定
    $target_file = $wp_upload_dir['path'] . '/' . basename($file_path);

    // ファイルをアップロードディレクトリに移動
    if (!copy($file_path, $target_file)) {
        return new WP_Error('upload_error', 'ファイルのアップロードに失敗しました。');
    }

    // 添付ファイルの配列を準備
    $attachment = array(
        'guid' => $wp_upload_dir['url'] . '/' . basename($file_path),
        'post_mime_type' => $filetype['type'],
        'post_title' => preg_replace('/\.[^.]+$/', '', basename($file_path)),
        'post_content' => '',
        'post_status' => 'inherit'
    );

    // 添付ファイルを登録
    $attachment_id = wp_insert_attachment($attachment, $target_file, $post_id);

    // 添付ファイルのメタデータを生成
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attachment_data = wp_generate_attachment_metadata($attachment_id, $target_file);
    wp_update_attachment_metadata($attachment_id, $attachment_data);

    return $attachment_id;
}

/**
 * ディレクトリ内のすべての画像をメディアライブラリに登録
 *
 * @param string $directory 画像ファイルが格納されているディレクトリのパス
 * @return void
 */
function register_images_in_directory($directory) {
    if (!is_dir($directory)) {
        return;
    }

    $files = scandir($directory);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }

        $file_path = $directory . '/' . $file;
        if (is_file($file_path)) {
            upload_image_to_media_library($file_path);
        }
    }
}

// 画像が格納されているディレクトリのパス
$directory = get_template_directory() . '/assets/img/items';

// ディレクトリ内のすべての画像をメディアライブラリに登録
register_images_in_directory($directory);