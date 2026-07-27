from sqlalchemy import Integer, String, Column
from models.almacen import miClaseBase

class TablaUsuarios(miClaseBase):
    __tablename__ = "Usuarios"
    
    id_usuario = Column(Integer, primary_key=True)
    usuario = Column(String(50), unique=True)
    contrasena = Column(String(30))


