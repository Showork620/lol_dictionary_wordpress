# local_labo_wordpress
ローカル環境でwordpressの実験をするためのプロジェクトです。



・最新パッチのjsonを取得
・不要なプロパティ削除
　→ items.jsonを作成 → これをgit管理

・has_detail フラグを手動で追加
・上記を元に、item_detail.json を追加
  → id 内に normal と aram
  → aram が無い場合は normal が参照される

カスタム投稿
・タイトル: string
・コンテント: string
・normal_detail: string or null
・aram_detail: string or null
・タグ: タグ
・アイキャッチ: アイキャッチ画像
・id: int
・colloq: string
・from: array<int> or null
・into: array<int> or null
・gold: int
・normal_item: bool
・aram_item: bool
