import os
from sqlalchemy import create_engine
from sqlalchemy.orm import declarative_base, sessionmaker
from dotenv import load_dotenv

load_dotenv()

USER = os.getenv("DB_USER")
PASSWORD = os.getenv("DB_PASSWORD")
HOST = os.getenv("DB_HOST")
PORT = os.getenv("DB_PORT")
NAME = os.getenv("DB_NAME")

RUTA_ALMACEN = f"postgresql+psycopg2://{USER}:{PASSWORD}@{HOST}:{PORT}/{NAME}"

motor = create_engine(
    RUTA_ALMACEN,
    pool_size=5,
    max_overflow=10,
    pool_pre_ping=True
)

FabricaLLaves = sessionmaker(bind=motor)
miClaseBase = declarative_base()

def abrir_puerta_bd():
    base_datos = FabricaLLaves()
    try:
        yield base_datos
    finally:
        base_datos.close()