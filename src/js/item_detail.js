// ============================================================
//  src/js/item_detail.js
// ============================================================

const itemButton = document.querySelectorAll('.js-item-button');
const modal = document.querySelector('.js-modal');
const modalCloseButtons = document.querySelectorAll('.js-modal-close');
let lastClickedCard;
let closeButtonTimer;

itemButton.forEach((button) => {
    button.addEventListener('click', function(event) {

        // closeButtons がクリックされて 100ms 以内なら処理をスキップ
        if (closeButtonTimer && Date.now() - closeButtonTimer < 100) {
            return;
        }

        // 最後にクリックしたカードを保存
        lastClickedCard = this;

        // 既に詳細モードの場合は処理をスキップ
        // input[type="submit"]の場合はpreventDefaultしない
        if (this.classList.contains('is-detail-mode')) {
            if (event.target.tagName === 'INPUT' && event.target.type === 'submit') {
                return;
            }
            event.preventDefault();
            return;
        }

        // クリックしたアイテムを詳細モードにする
        itemButton.forEach((button) => {
            button.classList.remove('is-detail-mode');
        });
        this.classList.add('is-detail-mode');
        modal.classList.add('is-detail-mode');

        // フォームにアイテム名をセット
        //選択したアイテム名を取得（buttonのdata-nameの値）
        const itemName = this.dataset.name;
        this.querySelector('.js-item-name-form').value = itemName;

        // bodyにスクロールを禁止
        document.body.style.overflow = 'hidden';
    });

    button.addEventListener('focus', function() {
        console.log('focus');
    });
});

modal.addEventListener('click', function() {
    this.classList.remove('is-detail-mode');
    itemButton.forEach((button) => {
        button.classList.remove('is-detail-mode');
    });

    // bodyにスクロールを許可
    document.body.style.overflow = '';
});

modalCloseButtons.forEach((modalCloseButton) => {
    modalCloseButton.addEventListener('click', function() {

        closeButtonTimer = Date.now();

        modal.classList.remove('is-detail-mode');
        itemButton.forEach((button) => {
            button.classList.remove('is-detail-mode');
        });

        // bodyにスクロールを許可
        document.body.style.overflow = '';

        // 最後にクリックしたカードにフォーカスを戻す
        lastClickedCard.focus();
    });
});

// 近接・遠隔の制御
const meleeRangeToggleButtons = document.querySelectorAll('.js-toggle-melee-ranged');

meleeRangeToggleButtons.forEach((button) => {

    button.addEventListener('click', function() {

        // この要素の親戚の .js-ability を取得
        const parentItemCard = this.closest('.js-item-button');
        const parentAbility = parentItemCard.querySelector('.js-ability');

        // button が checked なら parentAbilityに is-ranged を追加
        if (this.checked) {
            parentAbility.classList.add('is-ranged');
        } else {
            parentAbility.classList.remove('is-ranged');
        }
    });
});