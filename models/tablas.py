from sqlalchemy import Integer, String, Column
from almacen import miClaseBase

class TablaUsuarios(miClaseBase):
    __tablename__ = "Usuarios"
    
    id_usuario = Column(Integer, primary_key=True)
    usuario = Column(String, unique=True)
    contrasena = Column(String)


