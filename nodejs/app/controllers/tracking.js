const amazonPaapi = require('amazon-paapi');
const { products, users, errors } = require("../models");

exports.updateInfo = async () => {
	await users.findAll({where: {is_permitted: 1}})
	.then(users => {
		for (let user of users) {
			amazonTracking(user);
		}
	}).catch(err => {
		console.log("Cannot access user data", err);
	});
};

const amazonTracking = async (user) => {
	await products.findAll({ where: { user_id: user.id } })
	.then(items => {
		var index = 0;
		var len = items.length;

		var asins = [];
		for (const i of items) {
			asins.push(i.asin);
		}

		let checkInterval = setInterval(() => {
			users.findByPk(user.id)
			.then((data) => {
				let query = {};

				if (data.stop == 0) {
					query.len = len;
					if (index < len) {
						let checkItemInfo = new CheckItemInfo(user, asins.slice(index, (index + 10)));
						checkItemInfo.main();
						index += 10;
						
						query.trk_num = Math.min(len, index);
						query.round = data.round;
					} else {
						clearInterval(checkInterval);
						amazonTracking(user);
						index = 0;

						query.round = data.round + 1;
					}
				} else if (data.stop == 1) {
					index = 0;
					query.round = 0;
					query.trk_num = 0;
					clearInterval(checkInterval);
					amazonTracking(user);
				}
				users.update(query, {where: {id: user.id}});
			});
		}, 4 * 1000);
	}).catch (err => {
		console.log(err);
	});
};

class CheckItemInfo {
	constructor(user, code) {
		this.user = user;
		this.code = code;
	}

	async main() {
		const commonParameters = {
			PartnerTag: this.user.partner_tag,
			AccessKey: this.user.access_key,
			SecretKey: this.user.secret_key,
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

		await amazonPaapi.GetItems(commonParameters, requestParameters)
		.then((amazonData) => { // save data into db
			console.log('success not 429');
			if (amazonData.Errors !== undefined && amazonData.Errors.length > 0) {
				var error = amazonData.Errors;
				for (const e of error) {
					var query = {
						user_id: this.user.id,
						asin: e.Message.substr(11, 10),
						error: '無効な ASIN コード'
					};
					
					var condition = {
						user_id: this.user.id,
						asin: e.Message.substr(11, 10),
					};
					
					products.update(query, { where: condition });
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

					let condition = {
						user_id: this.user.id,
						asin: item.ASIN,
					};

					products.findAll({ where: condition })
					.then(async (data) => {
						// -------------------- update register price and target price base on current price and interval ----------------------
						var now = new Date();

						var changed_time = new Date(data[0].changed_time);
						if ((now - changed_time) > this.user.interval * 1000) {
							query.changed_time = now.getFullYear() + '.' + (now.getMonth() + 1) + '.' + now.getDate() + ' ' + now.getHours()  + ':' + now.getMinutes() + ':' + now.getSeconds();

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

							if (price == 0) {
								
							} else {
								query.tar_price = Math.floor(price * query.pro / 100);
							}
						}

						await products.update(query, { where: condition });

						//------------------- send line notification -------------------
						if (query.price > data[0].reg_price / 5 && query.price < data[0].tar_price && query.in_stock == '在庫あり。') {
							var amazonUrl = "https://www.amazon.co.jp/gp/offer-listing/" + data[0].asin;
							var notification = "現在価格[" + query.price + "円]が目標価格[" + data[0].tar_price + "円]以下になりました。";
							var keepaUrl = "https://keepa.com/#!product/5-" + data[0].asin;
							
							var lineNotify = require('line-notify-nodejs')(this.user.access_token);
														
							if (data[0].is_notified == 0) {
								lineNotify.notify({
									message: '\n\n' + amazonUrl + '\n\n' + notification + '\n\n' + keepaUrl,
								}).then(() => {
									query.is_notified = 1;
									
									var now = new Date();
									query.notified_time = now.getFullYear() + '.' + (now.getMonth() + 1) + '.' + now.getDate() + ' ' + now.getHours()  + ':' + now.getMinutes() + ':' + now.getSeconds();

									products.update(query, { where: condition });
	
									var note = {
										code: data[0].asin + ' : ' + notification,
										link: keepaUrl,
										recipient: this.user.id + ' : ' + this.user.family_name
									};
									errors.create(note);
								});
							} else if (data[0].is_notified == 1) {
								var now = new Date();
								
								var notified_time = new Date(data[0].notified_time);
								if ((now - notified_time) > data[0].inter * 1000) {
									lineNotify.notify({
										message: '\n\n' + amazonUrl + '\n\n' + notification + '\n\n' + keepaUrl,
									}).then(() => {
										
										query.notified_time = now.getFullYear() + '.' + (now.getMonth() + 1) + '.' + now.getDate() + ' ' + now.getHours()  + ':' + now.getMinutes() + ':' + now.getSeconds();
	
										products.update(query, { where: condition });
		
										var note = {
											code: data[0].asin + ' : ' + notification,
											link: keepaUrl,
											recipient: this.user.id + ' : ' + this.user.family_name
										};
										errors.create(note);
									});
								}
							}
						}
					})
					.catch(err => {
						console.log('notify error', err);
					});
				} catch (err) {
					console.log('forof item error', err);
				}
			}
		}).catch(err => {
			console.log('amazon tracking error', err.status, this.user.id);
		});
	}
}