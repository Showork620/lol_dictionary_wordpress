// ============================================================
//  src/js/item_detail.js
// ============================================================

const itemButton = document.querySelectorAll('.js-item-button');
const modal = document.querySelector('.js-modal');

itemButton.forEach((button) => {
    button.addEventListener('click', function() {

        if (this.classList.contains('is-detail-mode')) {
           return;
        }

        itemButton.forEach((button) => {
            button.classList.remove('is-detail-mode');
        });
        this.classList.add('is-detail-mode');

        modal.classList.add('is-detail-mode');
    });
});

modal.addEventListener('click', function() {
    this.classList.remove('is-detail-mode');
    itemButton.forEach((button) => {
        button.classList.remove('is-detail-mode');
    });
});