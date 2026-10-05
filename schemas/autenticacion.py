from pydantic import BaseModel


class DatosUrlGrabacion(BaseModel):
    url: str
