
import jwt
import os
from dotenv import load_dotenv
from datetime import datetime, timedelta
from fastapi import Depends, HTTPException, status
from fastapi.security import OAuth2PasswordBearer
from passlib.context import CryptContext

load_dotenv()


FIRMA_DEL_DIRECTOR = os.getenv("FIRMA_DEL_DIRECTOR")
if FIRMA_DEL_DIRECTOR is None:
    raise ValueError(
        "ERROR CRITICO: FIRMA_DEL_DIRECTOR no esta definida en el archivo .env. "
        "Ejecuta: openssl rand -hex 32 y agrégala al .env."
    )

ALGORITMO_JWT = "HS256"


lector_magnetico = OAuth2PasswordBearer(tokenUrl="login")

pwd_context = CryptContext(schemes=["bcrypt"], deprecated="auto")


# ── 1. ENCRIPTAR CONTRASEÑA ──────────────────────────────────────────────────
def encriptar_contrasena(contrasena_plana: str) -> str:
   
    return pwd_context.hash(contrasena_plana)


# ── 2. VERIFICAR CONTRASEÑA ──────────────────────────────────────────────────
def verificar_contrasena(contrasena_plana: str, hash_guardado: str) -> bool:
    
    return pwd_context.verify(contrasena_plana, hash_guardado)


# ── 3. EMITIR CREDENCIAL JWT ─────────────────────────────────────────────────
def emitir_credencial(datos_del_profesor: dict) -> str:
    
    datos_a_imprimir = datos_del_profesor.copy()
    datos_a_imprimir["exp"] = datetime.utcnow() + timedelta(hours=2)

    return jwt.encode(datos_a_imprimir, FIRMA_DEL_DIRECTOR, algorithm=ALGORITMO_JWT)


# ── 4. VALIDAR CREDENCIAL JWT ────────────────────────────────────────────────
def revisar_credencial_en_sistema(
    credencial_deslizada: str = Depends(lector_magnetico),
) -> int:
   
    try:
        datos_leidos = jwt.decode(
            credencial_deslizada,
            FIRMA_DEL_DIRECTOR,
            algorithms=[ALGORITMO_JWT],
        )
        id_del_profesor: int = datos_leidos.get("id_usuario")

        if id_del_profesor is None:
            raise HTTPException(
                status_code=status.HTTP_401_UNAUTHORIZED,
                detail="Esta credencial esta en blanco, no tiene tu numero de maestro.",
            )

        return id_del_profesor

    except jwt.ExpiredSignatureError:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Tu credencial ya vencio. Vuelve a iniciar sesion en el portal.",
        )
    except jwt.PyJWTError:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Credencial falsa detectada. Acceso al sistema denegado.",
        )