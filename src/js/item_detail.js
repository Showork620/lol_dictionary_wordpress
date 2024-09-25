// ============================================================
//  src/js/item_detail.js
// ============================================================

const itemButton = document.querySelectorAll('.js-item-button');
const modal = document.querySelector('.js-modal');
const modalCloseButtons = document.querySelectorAll('.js-modal-close');
let lastClickedCard;
let closeButtonTimer;

itemButton.forEach((button) => {
    button.addEventListener('click', function() {

        // closeButtons がクリックされて 100ms 以内なら処理をスキップ
        if (closeButtonTimer && Date.now() - closeButtonTimer < 100) {
            return;
        }

        // 最後にクリックしたカードを保存
        lastClickedCard = this;

        if (this.classList.contains('is-detail-mode')) {
           return;
        }

        itemButton.forEach((button) => {
            button.classList.remove('is-detail-mode');
        });
        this.classList.add('is-detail-mode');
        modal.classList.add('is-detail-mode');

        // bodyにスクロールを禁止
        document.body.style.overflow = 'hidden';
        modal.focus();
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