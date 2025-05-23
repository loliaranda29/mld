const nodemailer = require('nodemailer');
require('dotenv').config();

const transporter = nodemailer.createTransport({
  service: 'gmail',
  auth: {
    user: process.env.EMAIL_USER,
    pass: process.env.EMAIL_PASS
  }
});

async function enviarMailConfirmacion(destinatario) {
  const mailOptions = {
    from: `"Municipio Luján Digital" <${process.env.EMAIL_USER}>`,
    to: destinatario,
    subject: "Confirmá tu registro",
    html: `
      <h1>Confirmá tu registro</h1>
      <p>Hacé clic en el siguiente enlace para verificar tu correo:</p>
      <a href="https://milujan.ar/confirmar?email=${encodeURIComponent(destinatario)}">Confirmar correo</a>
    `
  };

  return transporter.sendMail(mailOptions);
}

module.exports = { enviarMailConfirmacion };
