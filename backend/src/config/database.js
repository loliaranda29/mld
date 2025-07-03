import { Sequelize } from 'sequelize';

const sequelize = new Sequelize('mld', 'usuario', 'contraseña', {
  host: 'localhost',
  dialect: 'mssql',
  dialectOptions: {
    options: {
      encrypt: false,
    },
  },
});

export default sequelize;
