from pydantic import BaseModel, Field
from typing import Optional
from datetime import date


# ── Login ────────────────────────────────────────────────────────────────────
class RevisarDatos(BaseModel):
    usuario:    str = Field(min_length=4, max_length=50)
    contrasena: str = Field(min_length=8, max_length=72)


# ── Registro de profesor ─────────────────────────────────────────────────────
class CrearCliente(BaseModel):
    nombre:     str = Field(min_length=2,  max_length=100)
    telefono:   str = Field(min_length=7,  max_length=20)
    correo:     str = Field(min_length=6,  max_length=254)
    usuario:    str = Field(min_length=4,  max_length=50)
    contrasena: str = Field(min_length=8,  max_length=72)


# ── Crear materia (NUEVO: horario estructurado en vez de texto libre) ───────
class CrearMateria(BaseModel):
    nombre:               str = Field(min_length=3, max_length=100)
    seccion:              str = Field(min_length=2, max_length=20)
    dia_semana:           int = Field(ge=0, le=6)          # 0=Lunes ... 6=Domingo
    hora_inicio:          str = Field(min_length=4, max_length=5)   # "08:00"
    hora_fin:             str = Field(min_length=4, max_length=5)   # "10:00"
    fecha_inicio_periodo: date
    semanas_duracion:     int = Field(default=11, ge=1, le=52)


# ── Inscripcion de estudiante ────────────────────────────────────────────────
class CrearEstudiante(BaseModel):
    nombre:        str = Field(min_length=2, max_length=100)
    telefono:      str = Field(min_length=7, max_length=20)
    correo:        str = Field(min_length=6, max_length=254)
    numero_cuenta: str = Field(min_length=4, max_length=20)
    modalidad:     str = Field(min_length=6, max_length=15)


# ── Marcar asistencia ────────────────────────────────────────────────────────
class MarcarAsistencia(BaseModel):
    token:         str            = Field(min_length=10, max_length=40)
    numero_cuenta: str            = Field(min_length=4,  max_length=20)
    lat:           Optional[float] = None
    lng:           Optional[float] = None


class ConfigurarUbicacionAula(BaseModel):
    lat: float
    lng: float



class CorregirAsistencia(BaseModel):
    id_estudiante: int
    fecha:         date
    presente:      bool

class AjustarFaltas(BaseModel):
    faltas: int = Field(ge=0)

class NotasEstudiante(BaseModel):
    notas: str = Field(max_length=500)