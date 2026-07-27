from pydantic import BaseModel, Field


class RevisarDatos(BaseModel):
    usuario: str = Field(min_length=6, max_length=10)
    contrasena: str = Field(min_length=8, max_length=15)
    
    