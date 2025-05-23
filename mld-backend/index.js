const express = require('express');
const cors = require('cors');
const { enviarMailConfirmacion } = require('./mailer');

const app = express();
app.use(cors());
app.use(express.json());

app.post('/api/enviar-confirmacion', async (req, res) => {
  const { email } = req.body;

  if (!email) return res.status(400).json({ error: 'Falta el email' });

  try {
    await enviarMailConfirmacion(email);
    res.json({ mensaje: 'Correo enviado' });
  } catch (err) {
    console.error('Error enviando el correo:', err);
    res.status(500).json({ error: 'Error al enviar el correo' });
  }
});

const PORT = process.env.PORT || 3001;
app.listen(PORT, () => console.log(`Servidor en http://localhost:${PORT}`));
