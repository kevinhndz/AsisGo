

import jwt
import bcrypt
import os
from dotenv import load_dotenv
from datetime import datetime, timedelta
from fastapi import Depends, HTTPException, status
from fastapi.security import OAuth2PasswordBearer

load_dotenv()


FIRMA_DEL_DIRECTOR = os.getenv("FIRMA_DEL_DIRECTOR")
if FIRMA_DEL_DIRECTOR is None:
    raise ValueError(
        "ERROR CRITICO: FIRMA_DEL_DIRECTOR no esta definida en .env. "
        "Ejecúta: openssl rand -hex 32  y agrégala."
    )

ALGORITMO_JWT = "HS256"

# Lee el token del header: Authorization: Bearer <token>
lector_magnetico = OAuth2PasswordBearer(tokenUrl="login")


# ── 1. ENCRIPTAR CONTRASEÑA ──────────────────────────────────────────────────
def encriptar_contrasena(contrasena_plana: str) -> str:
   
    salt = bcrypt.gensalt()
    hash_bytes = bcrypt.hashpw(contrasena_plana.encode("utf-8"), salt)
    return hash_bytes.decode("utf-8")   # guardamos el hash como texto en la BD


# ── 2. VERIFICAR CONTRASEÑA ──────────────────────────────────────────────────
def verificar_contrasena(contrasena_plana: str, hash_guardado: str) -> bool:
  
    return bcrypt.checkpw(
        contrasena_plana.encode("utf-8"),
        hash_guardado.encode("utf-8")
    )


# ── 3. EMITIR CREDENCIAL JWT ─────────────────────────────────────────────────
def emitir_credencial(datos_del_profesor: dict) -> str:
    
    payload = datos_del_profesor.copy()
    payload["exp"] = datetime.utcnow() + timedelta(hours=2)
    return jwt.encode(payload, FIRMA_DEL_DIRECTOR, algorithm=ALGORITMO_JWT)


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
                detail="Esta credencial no contiene un ID de usuario válido.",
            )
        return id_del_profesor

    except jwt.ExpiredSignatureError:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Tu sesión expiró. Vuelve a iniciar sesión.",
        )
    except jwt.PyJWTError:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Credencial inválida. Acceso denegado.",
        )