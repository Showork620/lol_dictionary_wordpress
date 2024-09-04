// 即時関数
(async function () {
	// 最新バージョンを取得
	const versionUrl = "https://ddragon.leagueoflegends.com/api/versions.json";
	async function getNewVersion() {
		const response = await fetch(versionUrl);
		const data = await response.json();
		return data[0];
	}

	const VERSION = await getNewVersion();
	const URL = `https://ddragon.leagueoflegends.com/cdn/${VERSION}/data/ja_JP/`;
	const ITEMDATA = await getOriginItemData(URL);

	async function getOriginItemData(preUrl) {
		const url = `${preUrl}item.json`;
		const response = await fetch(url);
		const json = await response.json();
		return json.data;
	}

	// 不要なアイテムやプロパティを削除
	Object.keys(ITEMDATA).forEach(key => {
		const item = ITEMDATA[key];
		if (item.description === "" && item.inStore === false) {
			delete ITEMDATA[key];
		}
		item.gold = item.gold.total;
		item.image = item.image.full;
		delete item.effect;
		delete item.plaintext;
		delete item.hideFromAll;
		delete item.consumed;
	});

	// サモりフとARAMを表す番号
	const mapNumber = {
		'Summoners Rift': 11,
		'Howling Abyss': 12
	}

	function getItems( itemOriginData, mapNumber ) {
		const newItemData = Object.keys(itemOriginData).reduce((object, key) => {
			const item = itemOriginData[key];
			if (item.maps[mapNumber]) {
				object[key] = item;
			}
			return object;
		}, {});

		return newItemData;
	}

	const nomalItems = getItems(ITEMDATA, mapNumber['Summoners Rift']);
	console.log("Nomal Items: ", nomalItems);

})();