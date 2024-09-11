// ============================================================
//  src/js/item_tab_button.js
// ============================================================

function choiceRole(buttonRole) {
    const items = document.querySelectorAll('.js-item');

    items.forEach(item => {
        const itemRoles = item.getAttribute('data-role').split(',');

        if (itemRoles.includes(buttonRole) || buttonRole === 'All') {
            item.classList.add('is-show');
        } else {
            item.classList.remove('is-show');
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // js-role-button を押すと、そのボタンがもつ data-role に対応する要素を表示する
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

    // js-dropdown を押すと、そのボタンがもつ data-tag に対応する要素を表示する
    const dropdown = document.querySelector('.js-tag-dropdown');
    dropdown.addEventListener('change', function() {
        const items = document.querySelectorAll('.js-item');

        items.forEach(item => {
            const itemTags = item.getAttribute('data-tag').split(',');
            if (itemTags.includes(dropdown.value)) {
                item.classList.add('is-show');
            } else {
                item.classList.remove('is-show');
            }
        });
    });


    // 初期表示
    choiceRole('Fighter');
});