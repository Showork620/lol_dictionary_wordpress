// ============================================================
//  src/js/item_tab_button.js
// ============================================================

function choiceRole(buttonRole) {
    const items = document.querySelectorAll('.js-item');

    items.forEach(item => {
        const itemRoles = item.getAttribute('data-role').split(',');

        if (itemRoles.includes(buttonRole)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
}

// js-role-button を押すと、そのボタンがもつ data-role に対応する要素を表示する
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.js-role-button');

    buttons.forEach(button => {
        button.addEventListener('click', function() {
            buttons.forEach(button => {
                button.classList.remove('is-choiced');
            });
            button.classList.add('is-choiced');
            choiceRole(button.getAttribute('data-role'));
        });
    });

    // 初期表示
    choiceRole('Fighter');
});