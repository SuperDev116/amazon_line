module.exports = (sequelize, Sequelize) => {
  const errorList = sequelize.define("errors", {
    code: {
      type: Sequelize.STRING
    },
    link: {
      type: Sequelize.STRING
    },
    recipient: {
      type: Sequelize.STRING
    },
  },
  { 
    timestamps: false
  });
  return errorList;
};