from sqlalchemy.orm import Session

from models.tablas import TablaUsuarios


def buscar_por_usuario(db: Session, usuario: str) -> TablaUsuarios | None:
    return db.query(TablaUsuarios).filter(TablaUsuarios.usuario == usuario).first()
