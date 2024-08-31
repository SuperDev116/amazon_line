const amazonPaapi = require('amazon-paapi');
const { products, users } = require("../models");

class GetProductInfo {
	constructor(user, code) {
		this.user = user;
		this.codes = code;
		this.code = [];
	}

	async main() {
		for (const c of this.codes) {
			this.code.push(c.asin);
		}

		const commonParameters = {
			AccessKey: 'AKIAJDFXFX3J46TVYBFA',
			SecretKey: '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',
			PartnerTag: 'dsad59-22',
			PartnerType: 'Associates',
			Marketplace: 'www.amazon.co.jp',
		};

		let requestParameters = {
			ItemIds: this.code,
			ItemIdType: 'ASIN',
			Condition: 'New',
			Resources: [
				'Offers.Summaries.LowestPrice',
				'Offers.Listings.Availability.Message',
				'Images.Primary.Small'
			],
		};

		var now = new Date();
		
		await amazonPaapi.GetItems(commonParameters, requestParameters)
		.then((amazonData) => {
			if (amazonData.Errors !== undefined && amazonData.Errors.length > 0) {
				var errors = amazonData.Errors;
				for (const e of errors) {
					var query = {
						user_id: this.user.id,
						asin: e.Message.substr(11, 10),
						error: '無効な ASIN コード'
					};
					
					products.create(query);
				}
			}

			var items = amazonData.ItemsResult.Items;
			for (const item of items) {
				try {
					let query = {};
					query.user_id = this.user.id;
					query.asin = item.ASIN;
					query.url = item.DetailPageURL;

					if (item.Images !== undefined) {
						query.image = item.Images.Primary.Small.URL;
					} else {
						query.image = '';
					}

					let price = 0;
					if (item.Offers !== undefined) {
						if (item.Offers.Listings[0].Availability !== undefined) {
							query.in_stock = item.Offers.Listings[0].Availability.Message;
						} else {
							query.in_stock = 'x';
						}
						
						if (item.Offers.Summaries[0].Condition.Value == 'New') {
							price = item.Offers.Summaries[0].LowestPrice.Amount;
						} else if (item.Offers.Summaries.length > 1 && item.Offers.Summaries[1].Condition.Value == 'New') {
							price = item.Offers.Summaries[1].LowestPrice.Amount;
						}
					} else {
						query.in_stock = 'x';
						price = 0;
					}

					query.price = price;
					query.reg_price = price;
					
					if (price > 0 && price < 5001) {
						query.pro = this.user.five;
					} else if (price > 5000 && price < 10001) {
						query.pro = this.user.ten;
					} else if (price > 10000 && price < 20001) {
						query.pro = this.user.twenty;
					} else if (price > 20000 && price < 30001) {
						query.pro = this.user.thirty;
					} else if (price > 30000) {
						query.pro = this.user.over;
					}
					query.tar_price = Math.floor(price * query.pro / 100);

					// if (price == 0) {
						for (const c of this.codes) {
							if (c.asin == query.asin) {
								query.reg_price = c.price;
								query.pro = c.pro;
								query.inter = c.interval;
								query.tar_price = Math.floor(c.price * c.pro / 100);
								break;
							}
						}
					// }

					query.changed_time = now.getFullYear() + '.' + (now.getMonth() + 1) + '.' + now.getDate() + ' ' + now.getHours()  + ':' + now.getMinutes() + ':' + now.getSeconds();
					
					products.create(query);
				} catch (err) {
					console.log('forof item error', err);
				}
			}
		}).catch(err => {
			console.log('amazon data err', this.code);
			for (const c of this.codes) {
				let query = {};
				query.user_id = this.user.id;
				query.asin = c.asin;
				query.reg_price = c.price;
				query.pro = c.pro;
				query.tar_price = Math.floor(c.price * c.pro / 100);
				query.changed_time = now.getFullYear() + '.' + (now.getMonth() + 1) + '.' + now.getDate() + ' ' + now.getHours()  + ':' + now.getMinutes() + ':' + now.getSeconds();
				query.inter = c.inter;
				products.create(query);
			}
		});
	}
}

const amazonInfo = async (user, codeList) => {
	try {
		await products.destroy({where: {'user_id': user.id}});

		var index = 0;
		var len = codeList.length;
		var inputInterval = setInterval(() => {
			if (index < len) {
				let getProductInfo = new GetProductInfo(user, codeList.slice(index, (index + 10)));
				getProductInfo.main();
				index += 10;

				let query = {};
				query.is_reg = 1;
				query.len = len;
				query.reg_num = Math.min(len, index);
				
				users.update(query, {where: {id: user.id}});
			} else {
				let query = {};
				query.is_reg = 0;
				query.round = 0;
				query.stop = 0;				
				setTimeout(() => {
					users.update(query, {where: {id: user.id}});
				}, 5000);
				clearInterval(inputInterval);
			}
		}, 2000);
	} catch (err) {
		console.log(err);
	}
};

exports.getInfo = (req, res) => {
	let reqData = JSON.parse(req.body.asin);
	users.findByPk(reqData.user_id)
	.then(user => {
		amazonInfo(user, reqData.codes);
	}).catch(err => {
		console.log('Cannot get user information.', err);
	});
};