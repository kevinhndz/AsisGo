
import smtplib
import os
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart
from dotenv import load_dotenv

load_dotenv()

CORREO_REMITENTE = os.getenv("EMAIL_USER")
CLAVE_APP_GMAIL  = os.getenv("EMAIL_PASSWORD")


def enviar_correo_grabacion(
    destinatarios: list,
    nombre_materia: str,
    url_video: str
) -> None:
  
    if not CORREO_REMITENTE or not CLAVE_APP_GMAIL:
        print("[correo.py] EMAIL_USER o EMAIL_PASSWORD no configurados. "
              "Saltando envío de correos.")
        return

    asunto = f"Grabación disponible: {nombre_materia}"

    cuerpo_html = f"""\
    <html><body>
    <p>Hola,</p>
    <p>La grabación de la clase de <strong>{nombre_materia}</strong>
       ya está disponible.</p>
    <p><a href="{url_video}">Ver grabación</a></p>
    <br>
    <p>Saludos,<br>Asis GO+</p>
    </body></html>
    """

    for correo_destino in destinatarios:
        try:
            mensaje = MIMEMultipart("alternative")
            mensaje["From"]    = CORREO_REMITENTE
            mensaje["To"]      = correo_destino
            mensaje["Subject"] = asunto
       
            mensaje.attach(MIMEText(cuerpo_html, "html"))

          
            with smtplib.SMTP("smtp.gmail.com", 587) as servidor:
                servidor.starttls()
                servidor.login(CORREO_REMITENTE, CLAVE_APP_GMAIL)
                servidor.send_message(mensaje)

            print(f"[correo.py] Correo enviado a {correo_destino}")

        except Exception as error:
           
            print(f"[correo.py] Error enviando a {correo_destino}: {error}")