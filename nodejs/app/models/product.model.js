module.exports = (sequelize, Sequelize) => {
  const productList = sequelize.define("products", {
    asin: {
      type: Sequelize.STRING
    },
    image: {
      type: Sequelize.STRING
    },
    reg_price: {
      type: Sequelize.INTEGER
    },
    price: {
      type: Sequelize.INTEGER
    },
    tar_price: {
      type: Sequelize.INTEGER
    },
    changed_time: {
      type: Sequelize.INTEGER
    },
    pro: {
      type: Sequelize.INTEGER
    },
    in_stock: {
      type: Sequelize.STRING
    },
    url: {
      type: Sequelize.STRING
    },
    inter: {
      type: Sequelize.STRING
    },
    user_id: {
      type: Sequelize.INTEGER
    },
    is_notified: {
      type: Sequelize.INTEGER
    },
    notified_time: {
      type: Sequelize.INTEGER
    },
    error: {
      type: Sequelize.STRING
    },
  },
  { 
    timestamps: false
  });
  return productList;
};