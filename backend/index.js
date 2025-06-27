import express from 'express';
import cors from 'cors';
import dotenv from 'dotenv';
import sequelize from './config/database.js';
import tramiteRouter from './routes/tramite.js';

dotenv.config();
const app = express();

app.use(cors());
app.use(express.json());

app.use('/api/tramites', tramiteRouter);

const PORT = process.env.PORT || 4000;

sequelize.sync().then(() => {
  app.listen(PORT, () => {
    console.log(`Servidor corriendo en puerto ${PORT}`);
  });
}).catch(err => console.error('Error conectando a SQL Server:', err));
