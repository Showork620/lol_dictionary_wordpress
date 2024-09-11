// ============================================================
//  src/js/item_tab_button.js
// ============================================================

let choicedRole = 'Fighter';
let choicedTag = 'All';

// アイテムが見つからない時に表示
const itemNotfound = document.querySelector('.js-item-notfound');
const unfilterButton = document.querySelector('.js-unfilter-button');
// フィルタ解除ボタンクリック
unfilterButton.addEventListener('click', () => {
    choicedRole = 'All';
    choicedTag = 'All';

    roleButtonList.forEach((roleButton) => {
        if (roleButton.dataset.role === 'All') {
            roleButton.classList.add('is-choiced');
        } else {
            roleButton.classList.remove('is-choiced');
        }
    });
    tagSelect.value = 'All';

    itemNotfound.classList.remove('is-show');
    filterItems();
});

// role ボタンクリック
const roleButtonList = document.querySelectorAll('.js-role-button');
roleButtonList.forEach((roleButton) => {
    // ボタンをクリック時
    roleButton.addEventListener('click', function() {
        // ボタンのハイライト切り替え
        roleButtonList.forEach((button) => {
            button.classList.remove('is-choiced');
        });
        this.classList.add('is-choiced');

        // 選択した role でフィルタリング
        choicedRole = roleButton.dataset.role;
        filterItems();
    });
});

// tag ドロップダウン選択
const tagSelect = document.querySelector('.js-tag-dropdown');
tagSelect.addEventListener('change', () => {
    choicedTag = tagSelect.value;
    filterItems();
});

// アイテムのフィルタリング
const filterItems = () => {
    const items = document.querySelectorAll('.js-item');
    let itemShowCount = 0;

    items.forEach((item) => {
        const itemRoleList = item.dataset.role ? item.dataset.role.split(',') : [];
        const itemTagList = item.dataset.tag ? item.dataset.tag.split(',') : [];

        if (
            (itemRoleList.includes(choicedRole) || choicedRole === 'All') &&
            (itemTagList.includes(choicedTag) || choicedTag === 'All')
        ) {
            item.classList.add('is-show');
            itemShowCount++;
        } else {
            item.classList.remove('is-show');
        }
    });

    // アイテムが見つからない時に「notfound」表示
    if (itemShowCount === 0) {
        itemNotfound.classList.add('is-show');
    } else {
        itemNotfound.classList.remove('is-show');
    }
};

// 初期表示
addEventListener('DOMContentLoaded', () => {
    filterItems();
});