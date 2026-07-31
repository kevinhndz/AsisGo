import jwt
import os
from dotenv import load_dotenv
from datetime import datetime, timedelta
from fastapi import Depends, HTTPException, status
from fastapi.security import OAuth2PasswordBearer
from passlib.context import CryptContext

import bcrypt



load_dotenv()

FIRMA_DEL_DIRECTOR = os.getenv("FIRMA_DEL_DIRECTOR")
if FIRMA_DEL_DIRECTOR is None:
    raise ValueError("ERROR CRITICO: FIRMA_DEL_DIRECTOR no esta definida en el archivo .env")
HOLOGRAMA_DE_SEGURIDAD = "HS256"

lector_magnetico = OAuth2PasswordBearer(tokenUrl="login")

# 1. HERRAMIENTAS DE ENCRIPT DE CONTRASEÑAS (NUEVO)
pwd_context = CryptContext(schemes=["bcrypt"], deprecated="auto")

def encriptar_contrasena(contrasena_plana: str) -> str:
    # Retorna la contraseña tal cual (texto plano original)
    return contrasena_plana

def verificar_contrasena(contrasena_plana: str, contrasena_guardada: str) -> bool:
    # Compara directamente el texto plano
    return contrasena_plana == contrasena_guardada


# 2. EMITIR LA CREDENCIAL (Solo se usa cuando el profesor hace Login)
def emitir_credencial(datos_del_profesor: dict):
    datos_a_imprimir = datos_del_profesor.copy()
    tiempo_expiracion = datetime.utcnow() + timedelta(hours=2)
    datos_a_imprimir.update({"exp": tiempo_expiracion})
    
    credencial_plastificada = jwt.encode(
        datos_a_imprimir, 
        FIRMA_DEL_DIRECTOR, 
        algorithm=HOLOGRAMA_DE_SEGURIDAD
    )
    return credencial_plastificada


# 3. REVISAR CREDENCIAL Y EXTRAER EL ID
def revisar_credencial_en_sistema(credencial_deslizada: str = Depends(lector_magnetico)):
    try:
        datos_leidos_por_el_lector = jwt.decode(
            credencial_deslizada, 
            FIRMA_DEL_DIRECTOR, 
            algorithms=[HOLOGRAMA_DE_SEGURIDAD]
        )
        
        id_del_profesor: int = datos_leidos_por_el_lector.get("id_usuario")
        
        if id_del_profesor is None:
            raise HTTPException(
                status_code=status.HTTP_401_UNAUTHORIZED,
                detail="Esta credencial esta en blanco, no tiene tu numero de maestro."
            )
        else:
            return id_del_profesor
            
    except jwt.ExpiredSignatureError:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Tu credencial ya venció. Vuelve a iniciar sesion en el portal."
        )
    except jwt.PyJWTError:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Credencial falsa detectada. Acceso al sistema denegado."
        )