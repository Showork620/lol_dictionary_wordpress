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

itemButton.forEach((button) => {
	button.addEventListener('click', function(event) {
		
		// アイテム変数の初期化
		lastClickedCard = this; // 最後にクリックしたカードを保存
		const isToggleButton = event.target.closest('.c-button-toggle');
		const iconPath = this.querySelector('.js-icon').getAttribute('src');
		const name = this.querySelector('.js-name').innerText;
		const gold = this.querySelector('.js-gold').innerText;
		const statsHtml = this.querySelector('.js-stats').innerHTML;
		const subHtml = this.querySelector('.js-sub').innerHTML;
		const abilityHtml = this.querySelector('.js-ability').innerHTML;
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
		modalInner.focus();
	});
});

modal.addEventListener('click', function() {
	this.classList.remove('is-active');
	itemButton.forEach((button) => {
		button.classList.remove('is-active');
	});

	// bodyにスクロールを許可
	document.body.style.overflow = '';
});

// クリックイベントのバブリングを停止
modalInner.addEventListener('click', function(event) {
	event.stopPropagation();
});

modalCloseButtons.forEach((modalCloseButton) => {
	modalCloseButton.addEventListener('click', function() {

		modal.classList.remove('is-active');
		itemButton.forEach((button) => {
			button.classList.remove('is-active');
		});

		// bodyにスクロールを許可
		document.body.style.overflow = '';

		// 最後にクリックしたカードにフォーカスを戻す
		lastClickedCard.focus();
		console.log('close button');
	});
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