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

// 使用不可、一覧に不要なアイテム
$UNAVAILABLE_ITEMS = [
    "1040", // オブシディアン エッジ
    "126697", // ヒュプリス<br>
    "1502", // 強化装甲
    "1506", // 強化装甲
    "2033", // コラプト ポーション
    "2403", // ミニオン吸収装置
    "3011", // ケミテック ピュートリファイアー
    "3400", // お前の取り分
    "4635", // リーチング リア
    "4636", // ナイト ハーベスター
    "4637", // 悪魔の抱擁
    "4641", // スターリング ワードストーン
    "6693", // プローラー クロウ
    "8001" // アナセマ チェイン
];

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

	// 使用不可、一覧に不要なアイテムを削除
	if ($item['description'] === "" && isset($item['inStore']) || in_array($key, $UNAVAILABLE_ITEMS)) {
		unset($ITEMDATA[$key]);
		continue;
	}
	// チャンピオン専用アイテム、aram・normalに未登場のアイテムを削除
	if (! $item['maps']['11'] && ! $item['maps']['12'] || isset($item['requiredChampion'])) {
		unset($ITEMDATA[$key]);
		continue;
	}

	// プロパティの整形・追加
	$item['id'] = $key;
	$item['gold'] = $item['gold']['total'];
	$item['nomal_item'] = $item['maps']['11'];
	$item['aram_item'] = $item['maps']['12'];

	// item['description']に<passive>が含まれているかどうか
	$item['has_passive'] = is_numeric(strpos($item['description'], '<passive>'));

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

echo 'item_data.json を出力しました: ' . date('Y-m-d H:i:s');