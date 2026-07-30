import jwt
import os
from dotenv import load_dotenv
from datetime import datetime, timedelta
from fastapi import Depends, HTTPException, status
from fastapi.security import OAuth2PasswordBearer
load_dotenv()


FIRMA_DEL_DIRECTOR = os.getenv("FIRMA_DEL_DIRECTOR")
if FIRMA_DEL_DIRECTOR is None:
    raise ValueError("ERROR CRITICO: FIRMA_DEL_DIRECTOR no esta definida en el archivo .env")
HOLOGRAMA_DE_SEGURIDAD = "HS256"

lector_magnetico = OAuth2PasswordBearer(tokenUrl="login")

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


"""

Una vez estando en este modulo corremos lector magnetico, que es lo que hace? pues sencillo
lo que hace es agarrar el header que vino en el JSON de JavaSript 

se miraba algo asi: 
{ "Authorization": "Bearer " + tokenGuardado }
lo que hace esa funcion de lector magentico es quitar el bearer y dejar solo el token sin nada mas!

Entonces en palabras simples: 

credencial_deslizada = token



"""

def revisar_credencial_en_sistema(credencial_deslizada: str = Depends(lector_magnetico)):
    
    try:
        #hacemos lo opuesto a encode que es decode, y volvemos a la key original
        datos_leidos_por_el_lector = jwt.decode(
            credencial_deslizada, 
            FIRMA_DEL_DIRECTOR, 
            algorithms=[HOLOGRAMA_DE_SEGURIDAD]
        )
        
    
        # como datos_leidos retorna un hashmap podemos acceder a su clave -> id_usuario
        id_del_profesor: int = datos_leidos_por_el_lector.get("id_usuario")
        
        # si esta vacia laznamo HTTP ERROR
        if id_del_profesor is None:
            raise HTTPException(
                status_code=status.HTTP_401_UNAUTHORIZED,
                detail="Esta credencial esta en blanco, no tiene tu numero de maestro."
            )
            # sino lo mandaos de vuelta a app.py para que tome el valor de: id_profesor
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
