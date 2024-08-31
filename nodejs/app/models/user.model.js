module.exports = (sequelize, Sequelize) => {
  const userList = sequelize.define("users", {
    email: {
      type: Sequelize.STRING
    },
    _token: {
      type: Sequelize.STRING
    },
    password: {
      type: Sequelize.STRING
    },
    role: {
      type: Sequelize.STRING
    },
    family_name: {
      type: Sequelize.STRING
    },
    partner_tag: {
      type: Sequelize.STRING
    },
    access_key: {
      type: Sequelize.STRING
    },
    secret_key: {
      type: Sequelize.STRING
    },
    interval: {
      type: Sequelize.INTEGER
    },
    five: {
      type: Sequelize.INTEGER
    },
    ten: {
      type: Sequelize.INTEGER
    },
    twenty: {
      type: Sequelize.INTEGER
    },
    thirty: {
      type: Sequelize.INTEGER
    },
    over: {
      type: Sequelize.INTEGER
    },
    is_reg: {
      type: Sequelize.INTEGER
    },
    reg_num: {
      type: Sequelize.INTEGER
    },
    trk_num: {
      type: Sequelize.INTEGER
    },
    file_name: {
      type: Sequelize.STRING
    },
    len: {
      type: Sequelize.INTEGER
    },
    round: {
      type: Sequelize.INTEGER
    },
    stop: {
      type: Sequelize.INTEGER
    },
    access_token: {
      type: Sequelize.STRING
    }
  },
  { 
    timestamps: false
  });
  return userList;
};