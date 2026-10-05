from fastapi import HTTPException, status
from sqlalchemy.orm import Session

from models.archivo_seguridad import emitir_credencial, verificar_contrasena
from repositories.usuarios import buscar_por_usuario


def autenticar_usuario(db: Session, usuario: str, contrasena: str) -> dict:
    usuario_db = buscar_por_usuario(db, usuario)
    if usuario_db is None:
        raise HTTPException(status_code=status.HTTP_401_UNAUTHORIZED, detail="Usuario no encontrado")
    if not verificar_contrasena(contrasena, usuario_db.contrasena):
        raise HTTPException(status_code=status.HTTP_401_UNAUTHORIZED, detail="Contrasena incorrecta!")
    datos = {"id_usuario": usuario_db.id_usuario, "usuario": usuario_db.usuario}
    return {"mensaje": "Bienvenido", "token": emitir_credencial(datos), "token_type": "bearer"}
