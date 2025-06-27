import { DataTypes } from 'sequelize';
import sequelize from '../config/database.js';

const Tramite = sequelize.define('Tramite', {
  nombre: DataTypes.STRING,
  descripcion: DataTypes.STRING,
  apiUrl: DataTypes.STRING,
  apiMethod: DataTypes.STRING
}, {
  timestamps: true
});

export default Tramite;
