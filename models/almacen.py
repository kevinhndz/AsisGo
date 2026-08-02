
import os
from sqlalchemy import create_engine
from sqlalchemy.orm import declarative_base, sessionmaker
from dotenv import load_dotenv

load_dotenv()


_REQUIRED_ENV = ("DB_USER", "DB_PASSWORD", "DB_HOST", "DB_PORT", "DB_NAME")
_missing = [v for v in _REQUIRED_ENV if not os.getenv(v)]
if _missing:
    raise EnvironmentError(
        f"ERROR CRITICO: Las siguientes variables de entorno faltan en .env: "
        f"{', '.join(_missing)}"
    )

DB_USER     = os.getenv("DB_USER")
DB_PASSWORD = os.getenv("DB_PASSWORD")
DB_HOST     = os.getenv("DB_HOST")
DB_PORT     = os.getenv("DB_PORT")
DB_NAME     = os.getenv("DB_NAME")


RUTA_ALMACEN = (
    f"postgresql+psycopg2://{DB_USER}:{DB_PASSWORD}@{DB_HOST}:{DB_PORT}/{DB_NAME}"
)

motor = create_engine(
    RUTA_ALMACEN,
    pool_size=5,
    max_overflow=10,
    pool_pre_ping=True,
)

FabricaLLaves = sessionmaker(bind=motor, autocommit=False, autoflush=False)


miClaseBase = declarative_base()


# ── Dependencia FastAPI ──────────────────────────────────────────────────────
def abrir_puerta_bd():
   
    base_datos = FabricaLLaves()
    try:
        yield base_datos
    finally:
        base_datos.close()