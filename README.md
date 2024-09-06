# local_labo_wordpress
ローカル環境でwordpressの実験をするためのプロジェクトです。


### アイテム一覧の作成

・最新パッチからjsonを取得
 ・不要なアイテムの削除
   ・除外リスト（UNAVAILABLE_ITEMS）
   ・説明とinStoreがないアイテム
   ・aram,サモリフに未登場のアイテム
   ・チャンピョン専用アイテム
 ・不要なプロパティを削除
 ・passiveがあるアイテムにフラグ追加
 ・管理しやすいようプロパティを整形
   → items.jsonを作成
   → これをgit管理

→ passiveリストについて
上記の items.json から item_passive.json 作成
item_passive.json は
keyをidに、normal_passive と aram_passive を持つ

・カスタム投稿
 ・タイトル: string
 ・コンテント: string
 ・normal_passive: string or null
 ・aram_passive: string or null
 ・タグ: タグ
 ・アイキャッチ: アイキャッチ画像
 ・id: int
 ・colloq: string
 ・from: array<int> or null
 ・into: array<int> or null
 ・gold: int
 ・normal_item: bool
 ・aram_item: bool

→ normal_passive,aram_passive のみ別jsonから取得


// 削除リスト（UNAVAILABLE_ITEMS）
1040: オブシディアン エッジ
126697: ヒュプリス<br>
1502: 強化装甲
1506: 強化装甲
2033: コラプト ポーション
2403: ミニオン吸収装置
3011: ケミテック ピュートリファイアー
3400: お前の取り分
4635: リーチング リア
4636: ナイト ハーベスター
4637: 悪魔の抱擁
4641: スターリング ワードストーン
6693: プローラー クロウ
8001: アナセマ チェイン
