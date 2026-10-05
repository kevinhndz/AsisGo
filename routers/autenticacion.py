from fastapi import APIRouter, Depends, status
from sqlalchemy.orm import Session

from models.almacen import abrir_puerta_bd
from models.security_guard import RevisarDatos
from services.autenticacion import autenticar_usuario


router = APIRouter()


@router.post("/login", status_code=status.HTTP_200_OK)
def login(json_recibido: RevisarDatos, base_datos: Session = Depends(abrir_puerta_bd)):
    return autenticar_usuario(base_datos, json_recibido.usuario, json_recibido.contrasena)
