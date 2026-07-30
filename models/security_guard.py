from pydantic import BaseModel, Field


class RevisarDatos(BaseModel):
    usuario: str = Field(min_length=6, max_length=10)
    contrasena: str = Field(min_length=8, max_length=15)


class CrearCliente(BaseModel):
    nombre: str = Field(min_length=2, max_length=100)
    telefono: str = Field(min_length=8, max_length=20)
    correo: str = Field(min_length=9, max_length=150)
    usuario: str = Field(min_length=6, max_length=10)
    contrasena: str = Field(min_length=8, max_length=15)


class CrearMateria(BaseModel):
    nombre: str = Field(min_length=5, max_length=50)
    seccion: str = Field(min_length=4, max_length=8)
    horario: str = Field(min_length=5, max_length=10)


class CrearEstudiante(BaseModel):
    nombre: str = Field(min_length=2, max_length=100)
    telefono: str = Field(min_length=8, max_length=20)
    correo: str = Field(min_length=9, max_length=150)
    numero_cuenta: str = Field(min_length=4, max_length=20)
    modalidad: str = Field(min_length=6, max_length=15)  # "presencial" | "virtual"
    
    