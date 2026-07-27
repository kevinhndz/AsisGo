import os
from sqlalchemy import create_engine
from sqlalchemy.orm import declarative_base, sessionmaker
from dotenv import load_dotenv

# 1. Cargamos el archivo .env para que Python lea las credenciales secretas
load_dotenv()

# 2. Extraemos cada valor usando os.getenv
USER = os.getenv("DB_USER")
PASSWORD = os.getenv("DB_PASSWORD")
HOST = os.getenv("DB_HOST")
PORT = os.getenv("DB_PORT")
NAME = os.getenv("DB_NAME")

#
RUTA_ALMACEN = f"mysql+pymysql://{USER}:{PASSWORD}@{HOST}:{PORT}/{NAME}"


motor = create_engine(RUTA_ALMACEN)
FabricaLLaves = sessionmaker(bind=motor)
miClaseBase = declarative_base()


def abrir_puerta_bd():
    base_datos = FabricaLLaves()
    try:
        yield base_datos
    finally:
        base_datos.close()