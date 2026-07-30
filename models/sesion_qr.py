import secrets
from datetime import datetime, timedelta

# Por cada materia guardamos una LISTA de tokens recientes (no solo el ultimo),
# cada uno con su propia fecha de vencimiento. Asi, aunque el QR visualmente
# se renueve cada 15s (por seguridad, para que no lo puedan compartir por foto
# y usarlo media hora despues), el token que el estudiante SI alcanzo a escanear
# sigue siendo valido por su propia ventana real de tiempo mientras completa
# el formulario (escribe numero de cuenta, espera el GPS, etc).
SESIONES_QR_ACTIVAS = {}

# Cada cuanto se genera un QR nuevo (lo que ve el profesor en pantalla)
INTERVALO_RENOVACION_SEGUNDOS = 15

# Cuanto tiempo sigue siendo valido un token YA ESCANEADO despues de generado.
# Debe ser mayor al intervalo de renovacion para dar margen real de uso.
DURACION_TOKEN_SEGUNDOS = 45


def generar_nuevo_token(id_materia: int) -> dict:
    token_nuevo = secrets.token_urlsafe(12)
    ahora = datetime.utcnow()
    expira_en = ahora + timedelta(seconds=DURACION_TOKEN_SEGUNDOS)

    tokens_de_la_materia = SESIONES_QR_ACTIVAS.setdefault(id_materia, [])

    # Limpiar tokens ya vencidos para no acumular basura en memoria
    tokens_de_la_materia[:] = [t for t in tokens_de_la_materia if t["expira"] > ahora]

    tokens_de_la_materia.append({"token": token_nuevo, "expira": expira_en})

    return {"token": token_nuevo, "expira_en_segundos": INTERVALO_RENOVACION_SEGUNDOS}


def token_es_valido(id_materia: int, token_recibido: str) -> bool:
    tokens_de_la_materia = SESIONES_QR_ACTIVAS.get(id_materia)

    if not tokens_de_la_materia:
        return False

    ahora = datetime.utcnow()
    for entrada in tokens_de_la_materia:
        if entrada["token"] == token_recibido and entrada["expira"] > ahora:
            return True

    return False