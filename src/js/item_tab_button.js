// ============================================================
//  src/js/item_tab_button.js
// ============================================================

let choicedRole = 'Fighter';
let choicedTag = 'All';

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
    items.forEach((item) => {
        const itemRoleList = item.dataset.role;
        const itemTagList = item.dataset.tag;

        if (
            (itemRoleList.includes(choicedRole) || choicedRole === 'All') &&
            (itemTagList.includes(choicedTag) || choicedTag === 'All')
        ) {
            item.classList.add('is-show');
        } else {
            item.classList.remove('is-show');
        }
    });

    console.log('role:', choicedRole, 'tag:', choicedTag);
};

// 初期表示
addEventListener('DOMContentLoaded', () => {
    filterItems();
});