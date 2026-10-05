import os
import socket


def obtener_ip_local() -> str:
    try:
        s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
        s.connect(("8.8.8.8", 80))
        ip = s.getsockname()[0]
        s.close()
        return ip
    except Exception:
        return "127.0.0.1"


def obtener_base_url() -> str:
    base_url_env = os.getenv("BASE_URL")
    if base_url_env:
        return base_url_env.rstrip("/")
    return f"http://{obtener_ip_local()}:8000"


BASE_URL = obtener_base_url()
NOMBRES_DIAS = ["Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado", "Domingo"]
