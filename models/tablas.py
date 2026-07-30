from sqlalchemy import Integer, String, Column, ForeignKey, Boolean, Date, DateTime
from models.almacen import miClaseBase

class TablaUsuarios(miClaseBase):
    __tablename__ = "usuarios"

    id_usuario = Column(Integer, primary_key=True)
    usuario = Column(String(50), unique=True)
    contrasena = Column(String(255))


class TablaClientes(miClaseBase):
    __tablename__ = "clientes"

    id_cliente = Column(Integer, primary_key=True)
    nombre = Column(String(100))
    telefono = Column(String(20))
    correo = Column(String(150), unique=True)
    id_usuario = Column(Integer, ForeignKey("usuarios.id_usuario"))


class TablaMaterias(miClaseBase):
    __tablename__ = "materias"

    id_materia = Column(Integer, primary_key=True)
    nombre = Column(String(50))
    seccion = Column(String(20), unique=True)
    horario = Column(String(25))

    lat_aula = Column(String(30), nullable=True)
    lng_aula = Column(String(30), nullable=True)
    id_usuario = Column(Integer, ForeignKey("usuarios.id_usuario"))


class TablaEstudiantes(miClaseBase):
    __tablename__ = "estudiantes"

    id_estudiante = Column(Integer, primary_key=True)
    nombre = Column(String(100))
    telefono = Column(String(20))
  
    correo = Column(String(150))
    numero_cuenta = Column(String(20), unique=True)
    modalidad = Column(String(15))  
    id_materia = Column(Integer, ForeignKey("materias.id_materia"))


class TablaAsistencia(miClaseBase):
   
    __tablename__ = "asistencia"

    id_asistencia = Column(Integer, primary_key=True)
    id_estudiante = Column(Integer, ForeignKey("estudiantes.id_estudiante"))
    id_materia = Column(Integer, ForeignKey("materias.id_materia"))
    fecha = Column(Date)
    presente = Column(Boolean, default=False)
    modalidad_usada = Column(String(15)) 


class TablaGrabaciones(miClaseBase):
    __tablename__ = "grabaciones"

    id_grabacion = Column(Integer, primary_key=True)
    id_materia = Column(Integer, ForeignKey("materias.id_materia"))
    url_video = Column(String(300))
    fecha_publicacion = Column(DateTime)
    