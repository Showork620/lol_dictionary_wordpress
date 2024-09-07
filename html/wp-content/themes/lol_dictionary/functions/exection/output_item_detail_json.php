<?php
/**
 * item_data.json から詳細説明用のJSONファイルを出力
 *
 * @return void
 */

 // item_data.json を読み込む
$itemDataPath = get_template_directory() . '/assets/json/item_data.json';
$itemDataJson = file_get_contents($itemDataPath);
$itemData = json_decode($itemDataJson, true);

// has_detail が true のアイテムをフィルタリングして新しい形式に変換
$filteredItems = [];
foreach ($itemData as $key => $item) {
    if (isset($item['has_detail']) && $item['has_detail'] === true) {
        $filteredItems[$key] = [
            "normal" => $item['description'],
            "aram" => ""
        ];
    }
}

// フィルタリングされたアイテムを新しい JSON として保存
$outputDir = get_template_directory() . '/assets/json/';
$outputFile = $outputDir . 'item_detail.json';
file_put_contents($outputFile, json_encode($filteredItems, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "フィルタリングされたアイテムが filtered_item_data.json に保存されました。\n";
