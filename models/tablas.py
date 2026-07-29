from sqlalchemy import Integer, String, Column, ForeignKey
from models.almacen import miClaseBase

class TablaUsuarios(miClaseBase):
    __tablename__ = "usuarios"
    
    id_usuario = Column(Integer, primary_key=True)
    usuario = Column(String(50), unique=True)
    contrasena = Column(String(30))


class TablaClientes(miClaseBase):
    __tablename__ = "clientes"
    
    id_cliente = Column(Integer, primary_key=True)
    nombre = Column(String(100))
    telefono = Column(String(20))
    id_usuario = Column(Integer, ForeignKey("usuarios.id_usuario"), unique=True)
    
class TablaMaterias (miClaseBase):
    __tablename__ = "materias"
    
    id_materia = Column(Integer, primary_key=True)
    nombre = Column(String(50))
    seccion = Column(String(20), unique= True)
    horario = Column(String(25))
    id_usuario = Column(Integer, ForeignKey("usuarios.id_usuario"), unique= True)
    
class TablaEstudiantes(miClaseBase):
    __tablename__ = "estudiantes"
    
    id_estudiante = Column(Integer, primary_key= True)
    nombre = Column (String(50))
    telefono = Column(String(20))
    modalidad = Column(String(15))
    id_materia = Column(Integer, ForeignKey("materias.id_materia"), unique=True)
    
    


    
    