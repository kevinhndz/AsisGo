from pydantic import BaseModel, Field


class RevisarDatos(BaseModel):
    usuario: str = Field(min_length=6, max_length=10)
    contrasena: str = Field(min_length=8, max_length=15)
    
class CrearCliente(BaseModel):
    nombre: str = Field(min_length=2, max_length=100)
    telefono: str = Field(min_length=8, max_length=20)
    usuario: str = Field(min_length=6, max_length=10)
    contrasena: str = Field(min_length=8, max_length=15)

    
class CrearClase(BaseModel):
    nombre: str = Field(min_length=3, max_length=100)
    codigo: str = Field(min_length=3, max_length=20)
    horario: str = Field(min_length=5, max_length=100)
    ubicacion: str = Field(min_length=3, max_length=100)

class RegistrarEstudiante(BaseModel):
    nombre: str = Field(min_length=2, max_length=100)
    numero_cuenta: str = Field(min_length=5, max_length=20)
    email: str
    modalidad: str  # "Presencial" o "Virtual"
    id_clase: int