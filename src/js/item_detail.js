// ============================================================
//  src/js/item_detail.js
// ============================================================

const itemButton = document.querySelectorAll('.js-item-button');

itemButton.forEach((button) => {
    button.addEventListener('click', function() {
        itemButton.forEach((button) => {
            button.classList.remove('is-detail-mode');
        });
        this.classList.add('is-detail-mode');
    });
});