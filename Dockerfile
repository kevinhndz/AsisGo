# ── Etapa 1: imagen base ─────────────────────────────────────────────────────

FROM python:3.12-slim

# ── Etapa 2: directorio de trabajo dentro del contenedor ─────────────────────

WORKDIR /app

# ── Etapa 3: instalar dependencias ───────────────────────────────────────────

COPY requirements.txt .

RUN pip install --no-cache-dir -r requirements.txt

# ── Etapa 4: copiar el resto del codigo ──────────────────────────────────────
# Ahora copiamos todo lo demás: app.py, models/, templates/, static/
# El .dockerignore (que crearemos) le dice a Docker que ignorar (venv, .env, etc.)
COPY . .

# ── Etapa 5: puerto que expone la app ────────────────────────────────────────
# EXPOSE es solo documentacion — le dice a quien lea el Dockerfile que puerto usa.
# No abre el puerto por si solo; eso lo hace el comando docker run con -p.
EXPOSE 8000

# ── Etapa 6: comando de arranque ─────────────────────────────────────────────
# Este es el comando que se ejecuta cuando el contenedor inicia.
# 
# --host 0.0.0.0 : escucha en TODAS las interfaces de red del contenedor,
#                  no solo en localhost. Sin esto, el contenedor no es accesible
#                  desde afuera aunque el puerto este abierto.
#
# --workers 2    : dos procesos paralelos. Para 512MB de RAM de mi droplet, 2 es el maximo
#                  seguro. 
#

CMD ["uvicorn", "app:app", "--host", "0.0.0.0", "--port", "8000", "--workers", "1"]