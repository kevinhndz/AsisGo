from sqlalchemy import Integer, String, Column, ForeignKey
from models.almacen import miClaseBase

class TablaUsuarios(miClaseBase):
    __tablename__ = "Usuarios"
    
    id_usuario = Column(Integer, primary_key=True)
    usuario = Column(String(50), unique=True)
    contrasena = Column(String(30))


class TablaClientes(miClaseBase):
    __tablename__ = "Clientes"
    
    id_cliente = Column(Integer, primary_key=True)
    nombre = Column(String(100))
    telefono = Column(String(20)) 
    id_usuario = Column(Integer, ForeignKey("Usuarios.id_usuario"), unique=True)

