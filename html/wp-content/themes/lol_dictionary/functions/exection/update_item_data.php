<?php
/**
 *  アイテムデータを取得、整形してJSONファイルに出力
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


// TODO: 以下の固定値は、別ファイルで管理する
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

// シンクロナイズドソウル対応
$SYNCHRONIZED_SOUL_ID = 3013;
$SYMBIOTIC_SOLES_ID = 3010;

// アイテムのロール（カテゴリ）
$ITEMS_ROLE = array(
    'Fighter' => array(
        6662, 2091, 6692, 6609, 3004, 3742, 3302, 3073, 3181, 3071, 3156, 3161, 6610, 3153, 3026, 3053, 6631, 3074, 3748, 2501, 3078, 6333, 3091
    ),
    'Marksman' => array(
        3046, 6675, 3085, 3094, 3091, 3004, 3087, 3115, 3124, 6673, 3036, 3033, 3302, 3508, 6672, 3156, 6676, 3032, 3153, 3026, 3139, 3031, 3072
    ),
    'Assassin' => array(
        6695, 3179, 3142, 6701, 3814, 6609, 3004, 6699, 6697, 6696, 3156, 6694, 6676, 3026, 6698
    ),
    'Mage' => array(
        3041, 3165, 6657, 3116, 3152, 3118, 4628, 2503, 3137, 6655, 3003, 4646, 3135, 3115, 6653, 4629, 3102, 3100, 4633, 4645, 3157, 3089
    ),
    'Tank' => array(
        3190, 3050, 3109, 3119, 3110, 8020, 3002, 6662, 3068, 3143, 3075, 6664, 2502, 4401, 3065, 2504, 3742, 3084, 6665, 3748, 2501, 3083
    ),
    'Support' => array(
        3165, 2065, 6620, 6617, 3190, 3050, 3109, 4005, 3504, 6616, 3107, 3222, 4643, 3110, 8020, 3002, 6621, 3075, 3869, 3870, 3871, 3876, 3877
    )
);

// tagsを翻訳
$TAGS_TRANSLATE = array(
	"AbilityHaste" => "スキルヘイスト",
	"Active" => "発動効果あり",
	"Armor" => "物理防御",
	"ArmorPenetration" => "物理防御貫通",
	"AttackSpeed" => "攻撃速度",
	"Aura" => "周囲効果",
	"Boots" => "移動速度",
	"Consumable" => "消費アイテム",
	"CooldownReduction" => "スキルヘイスト",
	"CriticalStrike" => "クリティカル",
	"Damage" => "攻撃力",
	"GoldPer" => "獲得ゴールド",
	"Health" => "体力",
	"HealthRegen" => "体力回復効果",
	"Jungle" => "ジャングル用アイテム",
	"Lane" => "初期購入アイテム",
	"LifeSteal" => "ライフスティール",
	"MagicPenetration" => "魔法防御貫通",
	"MagicResist" => "魔法防御",
	"Mana" => "マナ",
	"ManaRegen" => "マナ回復効果",
	"NonbootsMovement" => "移動速度",
	"OnHit" => "通常攻撃時効果",
	"Slow" => "スロウ効果",
	"SpellBlock" => "魔法防御",
	"SpellDamage" => "魔力",
	"SpellVamp" => "オムニヴァンプ",
	"Stealth" => "ステルス",
	"Tenacity" => "行動妨害耐性",
	"Trinket" => "トリンケット",
	"Vision" => "視界"
);

// stats用キーワード
$STATS_KEYWORD = ["体力", "マナ", "攻撃力", "魔力", "物理防御", "魔法防御", "移動速度", "攻撃速度", "スキルヘイスト", "クリティカル率", "クリティカルダメージ", "脅威", "物理防御貫通", "魔法防御貫通", "ライフ スティール", "基本体力自動回復", "基本マナ自動回復", "回復効果およびシールド量", "行動妨害耐性"];

// アイテムデータを取得
function getOriginItemData($preUrl) {
	$url = "{$preUrl}item.json";
	$response = file_get_contents($url);
	$json = json_decode($response, true);
	return $json['data'];
}

$ITEMDATA = getOriginItemData($URL);

$DESTINATION_LIST = [];

// 不要なアイテムやプロパティを削除
foreach ($ITEMDATA as $key => $item) {

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
	
	// シンクロナイズドソウル対応
	if ($key === $SYNCHRONIZED_SOUL_ID) {
		$item['specialRecipe'] = $SYMBIOTIC_SOLES_ID;
	}

	// Specialrecipeで指定されたアイテムをリストに追加
	if (isset($item['specialRecipe'])) {
		$DESTINATION_LIST[$item['specialRecipe']] = $key;
	}

	// プロパティの追加
	$item['id'] = $key;

	// desctiption からの抽出 ==========
	$description = $item['description'];

	// statsの追加
	$stats_list = [];
	foreach ($STATS_KEYWORD as $stats_key) {
		$stats_value = null;
		$first_split = explode($stats_key, $description);
	
		if (isset($first_split[1])) {
			$second_split = explode('<attention>', $first_split[1]);

			//"マナ自動回復","体力自動回復"の包含問題を解決
			if ($stats_key === "マナ" || $stats_key === "体力") {
				// $second_split[0] に "自動回復" が含まれていたらスキップ
				if (strpos($second_split[0], "自動回復") !== false) {
					continue;
				}
			}
	
			if (isset($second_split[1])) {
				$stats_value = explode('</attention>', $second_split[1])[0];
			}
		}
	
		if ($stats_value !== null) {
			$stats_list[$stats_key] = $stats_value;
			
			// 回復効果およびシールド量 → 回復効果&シールド量 に変換
			if ($stats_key === "回復効果およびシールド量") {
				$stats_list["回復効果&シールド量"] = $stats_list[$stats_key];
				unset($stats_list[$stats_key]);
			}
		}
	}
	$item['stats'] = $stats_list;

	// passive, active の追加
	$passive_list = [];
	$active_list = [];

	// passiveとactiveを抽出（ただし "<passive>「xxx」</passive>" は除外）
	preg_match_all('/<passive>(?!「)(.*?)<\/passive>(.*?)(?=<passive>(?!「)|<active>|<\/mainText>)/s', $description, $matches);	if (!empty($matches[0])) {
		foreach ($matches[0] as $match) {
			$passive_list[] = $match;
		}
	}
	preg_match_all('/<active>(.*?)<\/active>(.*?)(?=<passive>(?!「)|<active>|<\/mainText>)/s', $description, $matches);
	if (!empty($matches[0])) {
		foreach ($matches[0] as $match) {
			if (strpos($match, '<active>発動効果') === false) {
				$active_list[] = $match;
			}
		}
	}
	$item['passives'] = $passive_list;
	$item['actives'] = $active_list;

	
	// $ITEMS_ROLE に含まれる場合、role プロパティを追加 role プロパティは 数値の配列 で 複数持つことができる
	foreach ($ITEMS_ROLE as $role => $items) {
		if (in_array($key, $items)) {
			$item['role'][] = $role;
		}
	}

	// プロパティの整形
	$item['gold'] = $item['gold']['total'];
	$item['normal_item'] = $item['maps']['11'];
	$item['aram_item'] = $item['maps']['12'];


	// tags の編集 ====================

	// tags の翻訳
	$tags = $item['tags'];
	$translated_tags = [];
	foreach ($tags as $tag) {
		if (isset($TAGS_TRANSLATE[$tag])) {
			if (!in_array($TAGS_TRANSLATE[$tag], $translated_tags)) {
				$translated_tags[] = $TAGS_TRANSLATE[$tag];
			}
		} else {
			// 翻訳が見つからない場合は元のタグを使用
			$translated_tags[] = $tag;
		}
	}

	

	$item['tags'] = $translated_tags;

	// plaintext の訂正版があれば上書き
	$new_plaintext = file_get_contents(get_template_directory() . '/assets/json/item_plaintext.json');
	$new_plaintext_list = json_decode($new_plaintext, true);
	if (isset($new_plaintext_list[$key])) {
		$item['plaintext'] = $new_plaintext_list[$key];
	}
	
	// 不要なプロパティを削除
	unset($item['image']);
	unset($item['maps']);
	unset($item['effect']);
	unset($item['hideFromAll']);
	unset($item['consumed']);
}

// 完成後の jsonに destination プロパティを追加
foreach ($DESTINATION_LIST as $key => $value) {
	$ITEMDATA[$key]['destination'] = $value;
}

// JSONファイルを指定のディレクトリに出力
$outputDir = get_template_directory() . '/assets/json/';
$outputFile = $outputDir . 'item_data.json';
file_put_contents($outputFile, json_encode($ITEMDATA, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo 'item_data.json を出力しました: ' . date('Y-m-d H:i:s');