import smtplib
import os
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart
from dotenv import load_dotenv

load_dotenv()

CORREO_REMITENTE = os.getenv("EMAIL_USER")
CLAVE_APP_GMAIL = os.getenv("EMAIL_PASSWORD")


def enviar_correo_grabacion(destinatarios: list, nombre_materia: str, url_video: str):
    if not CORREO_REMITENTE or not CLAVE_APP_GMAIL:
        print("Email no configurado en .env, saltando envio...")
        return

    asunto = f"Grabacion disponible: {nombre_materia}"
    cuerpo = f"""
    Hola,

    La grabacion de la clase de {nombre_materia} ya esta disponible.

    Puedes verla aqui: {url_video}

    Saludos,
    Asis GO+
    """

    for correo_destino in destinatarios:
        try:
            mensaje = MIMEMultipart()
            mensaje["From"] = CORREO_REMITENTE
            mensaje["To"] = correo_destino
            mensaje["Subject"] = asunto
            mensaje.attach(MIMEText(cuerpo, "plain"))

            with smtplib.SMTP("smtp.gmail.com", 587) as servidor:
                servidor.starttls()
                servidor.login(CORREO_REMITENTE, CLAVE_APP_GMAIL)
                servidor.send_message(mensaje)

        except Exception as error:
            print(f"Error mandando correo a {correo_destino}: {error}")