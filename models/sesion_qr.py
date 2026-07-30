import secrets
from datetime import datetime, timedelta

SESIONES_QR_ACTIVAS = {}
DURACION_TOKEN_SEGUNDOS = 15


def generar_nuevo_token(id_materia: int) -> dict:
    token_nuevo = secrets.token_urlsafe(12)
    expira_en = datetime.utcnow() + timedelta(seconds=DURACION_TOKEN_SEGUNDOS)

    SESIONES_QR_ACTIVAS[id_materia] = {
        "token": token_nuevo,
        "expira": expira_en
    }

    return {"token": token_nuevo, "expira_en_segundos": DURACION_TOKEN_SEGUNDOS}


def token_es_valido(id_materia: int, token_recibido: str) -> bool:
    sesion = SESIONES_QR_ACTIVAS.get(id_materia)

    if sesion is None:
        return False

    if sesion["token"] != token_recibido:
        return False

    if datetime.utcnow() > sesion["expira"]:
        return False

    return True