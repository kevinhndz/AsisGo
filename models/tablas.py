from sqlalchemy import (
    Integer, String, Column, ForeignKey,
    Boolean, Date, DateTime
)


from models.almacen import miClaseBase


class TablaUsuarios(miClaseBase):
    __tablename__ = "usuarios"

    id_usuario = Column(Integer, primary_key=True)
    usuario    = Column(String(50), unique=True, nullable=False)
    contrasena = Column(String(255), nullable=False)



class TablaClientes(miClaseBase):
    __tablename__ = "clientes"

    id_cliente = Column(Integer, primary_key=True)
    nombre     = Column(String(100), nullable=False)
    telefono   = Column(String(20))
    correo     = Column(String(150), unique=True, nullable=False)
    id_usuario = Column(Integer, ForeignKey("usuarios.id_usuario"), nullable=False)



class TablaMaterias(miClaseBase):
    __tablename__ = "materias"

    id_materia = Column(Integer, primary_key=True)
    nombre     = Column(String(50), nullable=False)
    seccion    = Column(String(20), unique=True, nullable=False)

   
    dia_semana            = Column(Integer, nullable=True)
    hora_inicio           = Column(String(5), nullable=True)   # "08:00"
    hora_fin              = Column(String(5), nullable=True)   # "10:00"
    fecha_inicio_periodo  = Column(Date, nullable=True)
    semanas_duracion      = Column(Integer, nullable=True, default=11)

    lat_aula   = Column(String(30), nullable=True)
    lng_aula   = Column(String(30), nullable=True)
    id_usuario = Column(Integer, ForeignKey("usuarios.id_usuario"), nullable=False)



class TablaEstudiantes(miClaseBase):
    __tablename__ = "estudiantes"

    id_estudiante = Column(Integer, primary_key=True)
    nombre        = Column(String(100), nullable=False)
    telefono      = Column(String(20))
    correo        = Column(String(150), nullable=False)
    numero_cuenta = Column(String(20), unique=True, nullable=False)
    modalidad     = Column(String(15), nullable=False)   # 'presencial' | 'virtual'
    id_materia    = Column(Integer, ForeignKey("materias.id_materia"), nullable=False)

 
    notas         = Column(String(500), nullable=True)


class TablaAsistencia(miClaseBase):
    __tablename__ = "asistencia"

    id_asistencia    = Column(Integer, primary_key=True)
    id_estudiante    = Column(Integer, ForeignKey("estudiantes.id_estudiante"), nullable=False)
    id_materia       = Column(Integer, ForeignKey("materias.id_materia"), nullable=False)
    fecha            = Column(Date, nullable=False)
    presente         = Column(Boolean, default=False)
    modalidad_usada  = Column(String(15), nullable=True)
    dentro_del_rango = Column(Boolean, nullable=True, default=None)

   
    editado_manualmente = Column(Boolean, default=False)



class TablaGrabaciones(miClaseBase):
    __tablename__ = "grabaciones"

    id_grabacion      = Column(Integer, primary_key=True)
    id_materia        = Column(Integer, ForeignKey("materias.id_materia"), nullable=False)
    url_video         = Column(String(300), nullable=False)
    fecha_publicacion = Column(DateTime, nullable=False)