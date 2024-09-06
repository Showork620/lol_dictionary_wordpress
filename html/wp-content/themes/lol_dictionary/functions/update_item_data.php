<?php
/**
 *  アイテム投稿タイプの作成
 *
 * @return void
 */

// 最新バージョンを取得
function getNewVersion() {
	$versionUrl = "https://ddragon.leagueoflegends.com/api/versions.json";
	$response = file_get_contents($versionUrl);
	$data = json_decode($response, true);
	return $data[0];
}

$VERSION = getNewVersion();
$URL = "https://ddragon.leagueoflegends.com/cdn/{$VERSION}/data/ja_JP/";

// アイテムデータを取得
function getOriginItemData($preUrl) {
	$url = "{$preUrl}item.json";
	$response = file_get_contents($url);
	$json = json_decode($response, true);
	return $json['data'];
}

$ITEMDATA = getOriginItemData($URL);

// 不要なアイテムやプロパティを削除
foreach ($ITEMDATA as $key => &$item) {
	// チャンプ専用アイテムや説明がないアイテムを削除
	if ($item['description'] === "" && isset($item['inStore']) || isset($item['requiredChampion'])) {
		unset($ITEMDATA[$key]);
		continue;
	}

	// プロパティの整形
	$item['id'] = $key;
	$item['gold'] = $item['gold']['total'];
	$item['nomal_item'] = $item['maps']['11'];
	$item['aram_item'] = $item['maps']['12'];

	// 不要なプロパティを削除
	unset($item['image']);
	unset($item['maps']);
	unset($item['stats']);
	unset($item['effect']);
	unset($item['plaintext']);
	unset($item['hideFromAll']);
	unset($item['consumed']);
}

// JSONファイルを指定のディレクトリに出力
$outputDir = get_template_directory() . '/assets/json/';
$outputFile = $outputDir . 'item_data.json';
file_put_contents($outputFile, json_encode($ITEMDATA, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
