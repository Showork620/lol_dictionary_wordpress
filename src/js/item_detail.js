// ============================================================
//  src/js/item_detail.js
// ============================================================

const itemButton = document.querySelectorAll('.js-item-button');

// modal 関連
const modal = document.querySelector('#js-modal');
const modalCloseButtons = document.querySelectorAll('.js-modal-close');
const modalForm = document.querySelector('#js-modal-form');
const modalInner = document.querySelector('#js-detail');
let lastClickedCard;

function displayDetail(item) {
		// アイテム変数の初期化
		const iconPath = item.querySelector('.js-icon').getAttribute('src');
		const name = item.querySelector('.js-name').innerText;
		const gold = item.querySelector('.js-gold').innerText;
		const statsHtml = item.querySelector('.js-stats').innerHTML;
		const subHtml = item.querySelector('.js-sub').innerHTML;
		const abilityHtml = item.querySelector('.js-ability').innerHTML;
		// abilityHTML に <ranged> と <melee> が含まれているか
		const hasToggle = abilityHtml.includes('<ranged>') || abilityHtml.includes('<melee>');

		// モーダルの中身を書き換え
		modal.querySelector('#js-icon').setAttribute('src', iconPath);
		modal.querySelector('#js-name').innerText = name;
		modal.querySelector('#js-gold').innerText = gold;
		modal.querySelector('#js-stats').innerHTML = statsHtml;
		modal.querySelector('#js-sub').innerHTML = subHtml;
		modal.querySelector('#js-ability').innerHTML = abilityHtml;
		modal.querySelector('#js-toggle').classList.toggle('is-show', hasToggle);

		// クリックしたアイテムを詳細モードにする
		modal.classList.add('is-active');

		// フォームにアイテム名をセット
		//選択したアイテム名を取得（buttonのdata-nameの値）
		modalForm.querySelector('.js-item-name-form').value = name;

		// bodyにスクロールを禁止
		document.body.style.overflow = 'hidden';
}

// ページ読み込み時
window.addEventListener('DOMContentLoaded', function() {
	// URLのクエリパラメータがあれば
	const url = new URL(window.location.href);
	const itemId = url.searchParams.get('item');
	if (itemId) {
		// アイテム一覧の中から該当するアイテムを取得
		const item = document.getElementById(itemId);
		if (item) {
			displayDetail(item);
		} else {
			console.error('指定されたIDのアイテムが見つかりません');
		}
	}
});

// アイテム一覧からのクリックイベント
itemButton.forEach((button) => {
	button.addEventListener('click', function(event) {
		
		event.preventDefault(); // デフォルトのイベントをキャンセル
		lastClickedCard = this; // 最後にクリックしたカードを保存

		// urlにクエリパラメータを追加（アイテム毎のURL共有のため）
		const itemId = this.id;
		const url = new URL(window.location.href);
		url.searchParams.set('item', itemId);
		window.history.pushState({}, '', url);

		displayDetail(this);
		modalInner.focus();
	});
});

function modalClick(modal) {
	modal.classList.remove('is-active');
	itemButton.forEach((button) => {
		button.classList.remove('is-active');
	});

	// urlのクエリパラメータを削除
	const url = new URL(window.location.href);
	url.searchParams.delete('item');
	window.history.pushState({}, '', url);

	// bodyにスクロールを許可
	document.body.style.overflow = '';
}

modal.addEventListener('click', function() {
	modalClick(this);

	// bodyにスクロールを許可
	document.body.style.overflow = '';
});

modalCloseButtons.forEach((modalCloseButton) => {
	modalCloseButton.addEventListener('click', function() {
		modalClick(modal);

		// 最後にクリックしたカードにフォーカスを戻す
		lastClickedCard.focus();
	});
});

// クリックイベントのバブリングを停止
modalInner.addEventListener('click', function(event) {
	event.stopPropagation();
});

// 近接・遠隔の制御
const meleeRangeToggleButtons = document.querySelectorAll('.js-toggle-melee-ranged');
meleeRangeToggleButtons.forEach((button) => {

	button.addEventListener('click', function() {

		// この要素の親戚の .js-ability を取得
		const parentAbility = modalInner.querySelector('#js-ability');

		// button が checked なら parentAbilityに is-ranged を追加
		if (this.checked) {
			parentAbility.classList.add('is-ranged');
		} else {
			parentAbility.classList.remove('is-ranged');
		}
	});
});