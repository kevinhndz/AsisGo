from sqlalchemy import Integer, String, Column, ForeignKey
from models.almacen import miClaseBase
from sqlalchemy import DateTime, Boolean
from datetime import datetime

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
    
    

class TablaClases(miClaseBase):
    __tablename__ = "clases"
    
    id_clase = Column(Integer, primary_key=True)
    nombre = Column(String(100))  # Ej: "Español", 
    codigo = Column(String(20), unique=True)  # Ej: "ESP101"
    horario = Column(String(100))  # Ej: "Lunes 4:00 PM - 7:00 PM"
    ubicacion = Column(String(100))  # Ej: "Aula 201"
    id_profesor = Column(Integer, ForeignKey("usuarios.id_usuario"))
    fecha_creacion = Column(DateTime, default=datetime.now)


class TablaEstudiantes(miClaseBase):
    __tablename__ = "estudiantes"
    
    id_estudiante = Column(Integer, primary_key=True)
    nombre = Column(String(100))
    numero_cuenta = Column(String(20), unique=True)
    email = Column(String(100))
    modalidad = Column(String(20))  # "Presencial" o "Virtual"
    id_clase = Column(Integer, ForeignKey("clases.id_clase"))
    id_usuario = Column(Integer, ForeignKey("usuarios.id_usuario"))


class TablaAsistencia(miClaseBase):
    __tablename__ = "asistencia"
    
    id_asistencia = Column(Integer, primary_key=True)
    id_estudiante = Column(Integer, ForeignKey("estudiantes.id_estudiante"))
    id_clase = Column(Integer, ForeignKey("clases.id_clase"))
    fecha = Column(DateTime, default=datetime.now)
    presente = Column(Boolean, default=False)
    

