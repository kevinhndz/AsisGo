import jwt
import os
from dotenv import load_dotenv
from datetime import datetime, timedelta
from fastapi import Depends, HTTPException, status
from fastapi.security import OAuth2PasswordBearer
load_dotenv()


FIRMA_DEL_DIRECTOR = os.getenv("FIRMA_DEL_DIRECTOR")
HOLOGRAMA_DE_SEGURIDAD = "HS256"

lector_magnetico = OAuth2PasswordBearer(tokenUrl="login")

# 2. EMITIR LA CREDENCIAL (Solo se usa cuando el profesor hace Login)

def emitir_credencial(datos_del_profesor: dict):
    
    datos_a_imprimir = datos_del_profesor.copy()
    
    fin_del_semestre = datetime.utcnow() + timedelta(hours=2)
    
    datos_a_imprimir.update({"exp": fin_del_semestre})
    
    credencial_plastificada = jwt.encode(
        datos_a_imprimir, 
        FIRMA_DEL_DIRECTOR, 
        algorithm=HOLOGRAMA_DE_SEGURIDAD
    )
    
    return credencial_plastificada


#

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