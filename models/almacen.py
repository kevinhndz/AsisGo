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

RUTA_ALMACEN = f"mysql+pymysql://{USER}:{PASSWORD}@{HOST}:{PORT}/{NAME}"

# Optimizaciones de conexión para producción (Pools y Anti-desconexión)
motor = create_engine(
    RUTA_ALMACEN,
    pool_size=10,         # Mantiene 10 conexiones abiertas listas para usarse
    max_overflow=20,      # Permite 20 mas si hay un pico de trafico
    pool_recycle=1800,    # Reinicia las conexiones cada 30 minutos
    pool_pre_ping=True    # Verifica si la BD está viva antes de consultarla
)

FabricaLLaves = sessionmaker(bind=motor)
miClaseBase = declarative_base()

def abrir_puerta_bd():
    base_datos = FabricaLLaves()
    try:
        yield base_datos
    finally:
        base_datos.close()