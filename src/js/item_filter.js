// ============================================================
//  src/js/item_filter.js
// ============================================================

let choicedRole = 'Fighter';
let choicedTag1 = 'All';
let choicedTag2 = 'All';

// アイテムが見つからない時に表示
const itemNotfound = document.querySelector('.js-item-notfound');
const unfilterButton = document.querySelector('.js-unfilter-button');
// フィルタ解除ボタンクリック
unfilterButton.addEventListener('click', () => {
    choicedRole = 'All';
    choicedTag1 = 'All';
    choicedTag2 = 'All';

    roleButtonList.forEach((roleButton) => {
        if (roleButton.dataset.role === 'All') {
            roleButton.classList.add('is-choiced');
        } else {
            roleButton.classList.remove('is-choiced');
        }
    });
    tagSelect1.value = 'All';
    tagSelect2.value = 'All';

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
const tagSelect1 = document.querySelector('.js-tag-dropdown1');
const tagSelect2 = document.querySelector('.js-tag-dropdown2');
tagSelect1.addEventListener('change', () => {
    choicedTag1 = tagSelect1.value;
    filterItems();
});
tagSelect2.addEventListener('change', () => {
    choicedTag2 = tagSelect2.value;
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
            (itemTagList.includes(choicedTag1) || choicedTag1 === 'All') &&
            (itemTagList.includes(choicedTag2) || choicedTag2 === 'All')
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