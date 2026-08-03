# 🚀 Guía Paso a Paso — Despliegue Real de Asis GO+

Esta guía sigue exactamente el orden en que desplegamos el proyecto en el servidor, comando por comando, incluyendo los errores reales que salieron y cómo se resolvieron.

Puedes visitar el sistema entrando a : https://asisgo.duckdns.org/

---

## 🖥️ Infraestructura utilizada

| Qué | Detalle |
|---|---|
| Proveedor | DigitalOcean |
| Servidor | Droplet Basic — 1 vCPU, 512 MB RAM, 10 GB disco |
| Sistema operativo | Ubuntu 24 |
| IP pública | 159.65.223.239 |
| Costo | ~$4 USD/mes |

---

## 🏗️ Arquitectura de producción

Así es el flujo de una visita a mi aplicación:

Navegador del usuario
↓
Nginx (puerto 80/443)
"Recibe todas las visitas desde internet, maneja HTTPS"
↓
Docker → Uvicorn (puerto 8001 externo → 8000 interno)
"Servidor de producción de Python, corriendo dentro de un contenedor"
↓
FastAPI — app.py
"Lógica de la aplicación"
↓
Supabase (Postgres)
"Base de datos externa, conectada vía Session Pooler"



Todo lo de arriba (menos la base de datos, que vive en Supabase) corre dentro del mismo droplet de $4/mes.

---

## 0️⃣ PASO 0 — Comprar el servidor en DigitalOcean

1. Fui a digitalocean.com, creé una cuenta. (Pudo haber sido AWS, Azure)
2. Elijo imagen: Ubuntu 24.04 (LTS)
3. Plan: Basic, tipo Regular, el más económico (1 vCPU / 512 MB / 10 GB, ~$4/mes)
4. Método de autenticación: Vía SSH
5. Le pongo un hostname reconocible, ej. `asis-go-prod`
6. "Create Droplet"

En unos 60 segundos la IP pública fue asignada.

---

## 🔑 PASO 0.5 — Generar las llaves Pública Y Privada, y conectarme sin contraseña cada vez

Esto lo hago en mi computadora, en Git Bash.

**1.** Genero ambas llaves en una ruta segura (la carpeta por defecto de Windows suele bloquear permisos, así que uso una carpeta temporal limpia):
```bash
mkdir -p ~/.ssh_temp
ssh-keygen -t rsa -b 4096 -f ~/.ssh_temp/id_rsa
```
Cuando pregunte por la passphrase, presiono Enter dos veces para dejarla sin contraseña.

**2.** Copio la llave pública al servidor. Primero la muestro en mi terminal:
```bash
cat ~/.ssh_temp/id_rsa.pub
```
Copio todo el texto largo que aparece.

Entro a la consola web de mi droplet en DigitalOcean (Access → Launch Console) y ahí ejecuto:
```bash
mkdir -p ~/.ssh && chmod 700 ~/.ssh
echo "PEGO_AQUI_LA_LLAVE_PUBLICA_QUE_GENERE" >> ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys
```

**3.** Creo un alias en Git Bash para no escribir la ruta completa cada vez:
```bash
echo "servidor='ssh -i ~/.ssh_temp/id_rsa root@159.65.223.239'" >> ~/.bashrc
source ~/.bashrc
```

De ahora en adelante, cada vez que quiero entrar al servidor, solo escribo:
```bash
servidor
```

Todo lo que sigue en esta guía se ejecuta dentro de esa conexión SSH, no en mi computadora local.

---

## 🔄 PASO 1 — Actualizar el sistema operativo

```bash
apt update && apt upgrade -y
```
**Qué hace:** descarga la lista de paquetes disponibles e instala actualizaciones de seguridad.
**Cuándo:** siempre el primer comando en un servidor nuevo.

---

## 🐳 PASO 2 — Instalar Docker

```bash
curl -fsSL https://get.docker.com | sh
```
**Qué hace:** descarga y ejecuta el instalador oficial de Docker. Al final del proceso, el script corre automáticamente `docker version` y muestra el resultado — no se necesita correrlo aparte, ya viene incluido en el output.

⚠️ **Lo que se verá al final (solo se ignora):**


Es solo informativo.

---

## 🌿 PASO 3 — Instalar Git

```bash
apt install git -y
```
**Qué hace:** instala Git para poder clonar el repositorio de Asis Go+. Si ya estaba instalado, diría "is already the newest version" — no pasa nada, no es error.

---

## 📥 PASO 4 — Clonar el repositorio

**OJO:** antes de clonar hay que asegurarse de que los `requirements.txt` estén al día, sino Docker los va a rechazar y no va a levantar el contenedor.

```bash
cd /app && git clone https://github.com/TU-USUARIO/TU-REPO.git .
```

⚠️ **Problema real que tuve:** si `/app` ya tiene algo adentro (por ejemplo, un proyecto viejo o otro repo), este comando falla con: fatal: destination path '.' already exists and is not an empty directory.

**Cómo lo resolví:**
```bash
ls -la /app/NOMBRE_CARPETA_VIEJA      # primero revisé qué había
rm -rf /app/NOMBRE_CARPETA_VIEJA      # si es basura, se borra
cd /app && git clone https://github.com/TU-USUARIO/TU-REPO.git .
```

**Verifico que se clonó bien:**
```bash
find /app -maxdepth 3 -not -path '*/.git*'
```
Esto muestra el árbol completo de carpetas — confirma que están `app.py`, `models/`, `templates/`, `static/`, `requirements.txt`, `Dockerfile`.

---

## 🔒 PASO 5 — Verificar seguridad de Git ANTES de meter contraseñas reales

Como el repositorio es público, esto es obligatorio antes de continuar:

```bash
cd /app
cat .gitignore                       # confirma que .env está en la lista
git status                           # debe decir "nothing to commit, working tree clean"
git ls-files | grep -i env           # NO debe mostrar nada
```

Si `git ls-files | grep -i env` muestra algo, significa que `.env` quedó guardado en el historial de Git en algún momento — hay que sacarlo con `git rm --cached .env` antes de seguir. Si no muestra nada (como en nuestro caso), se procede.

---

## 🔐 PASO 6 — Crear el archivo `.env` (dentro del servidor)

**Paso #1:**
```bash
openssl rand -hex 32
```
Da algo como: `ce7c78a1b74ce3ff900388d63783e1868e65705c41c0ff04dc224c99f31c5d9f` — se copia ese resultado.

**Paso #2:** Crear y editar de golpe. Es la forma segura, evita errores de edición manual:
```bash
cat > /app/.env << 'EOF'
DB_USER=postgres._PROJECT_REF
DB_PASSWORD=_password_de_supabase
DB_HOST=aws-0-TU-REGION.pooler.supabase.com
DB_PORT=5432
DB_NAME=postgres
FIRMA_DEL_DIRECTOR=el_hash_generado_arriba
EMAIL_USER=correo@gmail.com
EMAIL_PASSWORD=_app_password_gmail
EOF
```

⚠️ **Verificar siempre, antes de continuar:**
```bash
cat -A /app/.env
```

---

## 🗄️ PASO 7 — Configurar Supabase

⚠️ **El error:**
Al principio usé el host de conexión directa (`db.xxxxx.supabase.co`) y la app me escupía el error `Network is unreachable`. Después de investigar, me di cuenta de que ese host me estaba mandando a una dirección IPv6, y mi droplet de DigitalOcean solo tiene configurado IPv4 por defecto. No había forma de que conectara.

**✅ Cómo lo solucioné:**
Tuve que cambiar la conexión directa por el Session Pooler de Supabase para forzar IPv4. Los pasos que seguí en el dashboard fueron:

1. Fui a mi proyecto en Supabase y arriba a la derecha le di al botón verde **Connect**.
2. En la pestaña de opciones, busqué **Connection Method** y cambié la opción de **Direct Connection** a **Session pooler**.
3. Nota para el futuro: elegir **Session** y **NO Transaction**, porque FastAPI necesita mantener las conexiones abiertas. Transaction es solo para funciones serverless tipo lambdas.
4. Copié los nuevos parámetros de conexión que me generó abajo:
   - **Host:** cambió a algo tipo `xxx.pooler.supabase.com`
   - **Port:** 5432
   - **User:** viene con un prefijo largo: `postgres.MI_ID_DE_PROYECTO` (no poner solo "postgres")

Con estos datos listos, fui a mi archivo `.env` en el servidor, los actualicé y por fin levantó la base de datos.

---

## 📦 PASO 8 — Construir la imagen y levantar el contenedor con Docker

**¿Qué es esto y para qué lo uso?**
Uso Docker para empaquetar toda mi app de FastAPI con sus dependencias (Python, librerías, etc.) dentro de un contenedor aislado. Esto me asegura que la aplicación corra exactamente igual en el servidor que en mi computadora local, sin pelearme con versiones de software ni configuraciones del sistema operativo del droplet. Con Docker Compose, gestiono el ciclo de vida del contenedor con comandos simples.

**Los comandos que ejecuté:**

Primero, me moví a la carpeta del proyecto. Si es la primera vez que levanto el entorno, tengo que crear el archivo `docker-compose.yml` que define cómo va a correr el contenedor. Lo armé directo en la terminal con este comando:

```bash
cat > /app/docker-compose.yml << 'EOF'
services:
  app:
    build: .
    restart: always
    env_file: .env
    ports:
      - "8001:8000"
EOF
```

**🧠 La lógica con los puertos (el porqué del 8001:8000):**
Aquí configuré un mapeo de puertos. El segundo número (8000) es el puerto interno donde la app de FastAPI (con Uvicorn) está escuchando dentro del contenedor; ese no se toca. El primer número (8001) es el puerto externo del servidor. Usé el 8001 a propósito porque es el puerto que ya tenía abierto y habilitado en el firewall del droplet, así que el tráfico entra por ahí y Docker lo redirige automáticamente al contenedor. El parámetro `restart: always` asegura que si el servidor se reinicia, mi app vuelva a levantar sola.

Una vez creado el archivo, arranqué el proceso de construcción y despliegue en segundo plano con:

```bash
cd /app
docker compose up -d --build
```

---

## ✅ PASO 9 — Confirmar que la app responde DENTRO del servidor

```bash
curl http://localhost:8000
```
(Uso el puerto interno, 8000, no el 8001 externo — porque se prueba dentro del mismo servidor).

Si devuelve HTML, la app está viva. Este paso es clave: si esto funciona pero desde afuera no carga, el problema es de red/firewall, no de la app.

---

## 💾 PASO 10 — Crear swap (memoria de emergencia, importante en droplets pequeños)

```bash
fallocate -l 1G /swapfile
chmod 600 /swapfile
mkswap /swapfile
swapon /swapfile
echo '/swapfile none swap sw 0 0' | tee -a /etc/fstab
free -h
```
Confirmo con `free -h` que la línea `Swap:` muestre el total creado (ej. `1.0Gi`).

---

## 🧱 PASO 11 — Revisar y arreglar el Firewall

```bash
ufw status
```

⚠️ **Problema real que tuve:** el firewall solo tenía abiertos `22, 80, 8001, 8080` — pero la app corría en el puerto `8000` (sin el "1"). Por eso no cargaba desde afuera al probar con ese puerto.

**✅ Solución que usé:** en vez de abrir el 8000, cambié el mapeo de Docker para usar el puerto que YA estaba permitido (`8001:8000` en el `docker-compose.yml`, ver Paso 8). Así no tuve que tocar el firewall en ese momento.

Para ver los puertos numerados y poder borrar por número (más confiable que por nombre):
```bash
ufw status numbered
```

Para borrar (ejemplo, quitar puertos que ya no se necesita — siempre se borra del número más alto al más bajo, porque la numeración se recorre después de cada borrado):
```bash
ufw delete 9
ufw delete 8
ufw delete 4
ufw delete 3
```

---

## 🌐 PASO 12 — Dominio gratuito con DuckDNS

1. Fui a duckdns.org
2. Creé un subdominio (ejemplo: `asisgo` → me da `asisgo.duckdns.org`)
3. En el campo de IP, puse la IP pública del droplet (se ve con `curl ifconfig.me` desde el servidor, o en el panel de DigitalOcean)
4. Le di "update ip"

---

## 🔧 PASO 13 — Instalar y configurar Nginx

```bash
apt install nginx certbot python3-certbot-nginx -y
```

```bash
cat > /etc/nginx/sites-available/asisgo << 'EOF'
server {
    listen 80;
    server_name TU_SUBDOMINIO.duckdns.org;

    location / {
        proxy_pass http://localhost:8001;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
EOF
ln -sf /etc/nginx/sites-available/asisgo /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default
nginx -t && systemctl reload nginx
```

**Nota:** `proxy_pass http://localhost:8001` — uso el puerto EXTERNO que definí en `docker-compose.yml` (Paso 8), no el interno.

`nginx -t` prueba que la configuración esté bien escrita ANTES de aplicarla — si dice "syntax is ok" y "test is successful", todo bien.

---

## 🔒 PASO 14 — Activar HTTPS con Certbot

```bash
certbot --nginx -d TU_SUBDOMINIO.duckdns.org --non-interactive --agree-tos -m TU_CORREO_REAL@gmail.com --redirect
```

**Señal de éxito:** Successfully deployed certificate for TU_SUBDOMINIO.duckdns.org
Congratulations! You have successfully enabled HTTPS


---

## 🧹 PASO 15 — Limpiar el firewall (dejar solo lo necesario)

Ahora que Nginx maneja el tráfico por 80/443, ya no necesito el puerto de la app expuesto directo:

```bash
ufw status numbered
```

Borro los puertos sueltos que ya no necesitaba (el de la app, cualquier otro proyecto viejo), del número más alto al más bajo:
```bash
ufw delete NUMERO
```

Agrego 443 por si no estaba:
```bash
ufw allow 443/tcp
```

Al final debería quedar solo con: `22`, `80`, `443` (y sus versiones `(v6)`).

---

## 🆘 Resumen de troubleshooting — comando exacto según el síntoma

| Síntoma | Comando de diagnóstico |
|---|---|
| ❓ No sé si la app está corriendo | `docker ps` |
| 🔍 Quiero ver el error exacto | `docker compose logs --tail=50 app` |
| 🗄️ Error de conexión a base de datos | `PGPASSWORD='xxx' psql -h HOST -p 5432 -U USER -d postgres -c "SELECT 1;"` |
| 🚧 502 Bad Gateway | `docker ps` → `docker compose logs --tail=50 app` → `curl http://localhost:PUERTO_INTERNO` |
| 🌐 Dominio no carga (DNS_PROBE_FINISHED_NXDOMAIN) | `nslookup TU_DOMINIO` en el servidor, y dnschecker.org desde el navegador |
| 🔒 Certificate error en la IP directa | Normal, uso el dominio, nunca la IP |
| 🐢 Servidor lento / se congela | `free -h` (reviso RAM y swap) |
| ⚙️ `.env` editado pero sin efecto | `docker compose up -d --build` (las variables se cargan solo al iniciar) |
| 🚪 Ver qué puertos están abiertos | `ufw status numbered` |
| ⏸️ Apagar todo sin perder datos | `docker compose down` |
| 💽 Espacio en disco | `df -h` |
| 🔑 "Too many authentication failures" en Supabase | Espero 10-20 min sin reintentar, luego pruebo con `psql` antes que con Docker |


# Siguiente Paso: Guía de como crear una  CI/CD: De Cero a Producción (Con Git-Hub Actions) 

Esta guía explica paso a paso cómo crear un CI/CD pipeline y automatizar el despliegue de la API (FastAPI + Docker) en un servidor remoto (Droplet). La seguí mientras lo hacía y anoté todo lo que fue pasando, incluyendo los errores que encontré.

---

## Paso 1: Verificar y Sincronizar las Llaves SSH

Antes de hacer cualquier otra cosa, hay que asegurarse de que el servidor "reconozca" a la PC. Esto se hace con llaves SSH.

### 1. Ver la llave pública en tu PC local (Git Bash)

```bash
cat ~/.ssh/id_rsa.pub
```

Esto muestra la "identidad pública", que es lo que se le va a dar al servidor para que se pueda abrir.



---

### 2. Ver las llaves autorizadas en el servidor (Droplet)


```bash
cat ~/.ssh/authorized_keys
```

Esto muestra qué PCs tienen permiso de conectarse. Aquí es donde hay que fijarse si la llave  publica aparece o no.

---

### 3. Agregar tu llave al servidor (si no está)

Si lo que salió en la PC **no aparece** dentro del archivo del servidor, la conexión va a fallar. La solución es copiar la llave de la PC y pegarla en este comando dentro del servidor:

```bash
echo "TU_LLAVE_PUBLICA_AQUI" >> ~/.ssh/authorized_keys
```

Esto agrega tu llave a la "lista de invitados" del servidor. Sin este paso, GitHub Actions va a dar el error `handshake failed`. Si las dos se parecen se puede omitir este paso.

---

## Paso 2: Guardar la Llave Privada en GitHub

Ahora le damos a GitHub la llave privada para que pueda conectarse al servidor en mi nombre.

### 1. Copiar la llave privada desde tu PC

```bash
cat ~/.ssh/id_rsa
```

Aqui hay que copiar **todo**, exactamente desde `-----BEGIN OPENSSH PRIVATE KEY-----` hasta `-----END OPENSSH PRIVATE KEY-----`, sin dejar espacios de más al inicio ni al final.

---

### 2. Guardarla como Secret en GitHub

Pasos:

Ir al  repositorio en GitHub → **Settings** → **Secrets and variables** → **Actions** → **New repository secret**

Crea estos tres secrets:

| Nombre | Valor |
|---|---|
| `DROPLET_IP` | La IP de tu servidor , ya sea AWS, Do, Azure (ej. `159.65.223.239`) |
| `DROPLET_USER` | El usuario del servidor (ej. `root`) | siempre sera root por defecto.
| `SSH_PRIVATE_KEY` | La llave Privada para que pueda acceder|

---

## Paso 3: Crear el Archivo YAML de Automatización

Este archivo le dice a GitHub qué hacer automáticamente cada vez que se suba  un git push.

### 1. Crear la carpeta y el archivo

Dentro del proyecto local, se va crear exactamente esta ruta:

```
.github/workflows/deploy.yml
```

> **¿Por qué tiene que ser exactamente esa ruta?** Porque GitHub está configurado para buscar esa carpeta específica. Si la llamas de otra manera o se pone en otro lugar, GitHub la ignora completamente y el despliegue nunca va a ejecutarse.

---

### 2. Pegar el código YAML


```yaml
name: Deploy to Droplet

on:
  push:
    branches:
      - main 

jobs:
  deploy:
    runs-on: ubuntu-latest

    steps:
      - name: Checkout del código
        uses: actions/checkout@v4

      - name: Desplegar en el Droplet vía SSH
        uses: appleboy/ssh-action@v0.1.10
        with:
          host: ${{ secrets.DROPLET_IP }}
          username: ${{ secrets.DROPLET_USER }}
          key: ${{ secrets.SSH_PRIVATE_KEY }}
          script: |
            cd /app
            git pull origin main
            docker build -t app-fastapi .
            docker stop api-container || true
            docker rm api-container || true
            docker run -d -p 8000:8000 --name api-container --env-file .env app-fastapi
```

**¿Qué hace cada parte importante?**

- `docker stop ... || true` → Detiene el contenedor anterior. El `|| true` le dice "si el contenedor no existe, no pares ni des error, sigue".
- `docker build` → Empaqueta la API con los cambios nuevos.
- `docker run -d` → Arranca la API en segundo plano en el puerto 8000.

Sin un cCI/CD tendria que entrar por SSH y hacerlo manual, poco eficiente.

---

## Paso 4: Subir el Código y Activar el Pipeline

Con todo configurado, solo falta hacer push. 

```bash
# 1. Registrar el archivo nuevo
git add .github/workflows/deploy.yml

# 2. Crear el punto de guardado
git commit -m "Agrega pipeline CI/CD y despliega app"

# 3. Subir a GitHub (esto activa el Action automáticamente)
git push origin main
```

El `git push` es el gatillo. Como en el YAML configuramos `on: push: branches: - main`, GitHub Actions arranca en cuanto detecta el push.

---

## Paso 5: Verificar y Resolver Errores

### 1. Revisar el resultado en GitHub

Ve a tu repositorio → pestaña **Actions**.

- 🟡 Círculo girando = está corriendo
- ✅ Verde = éxito, todo funcionó
- ❌ Rojo = algo falló, hay que revisar

---

### 2. Errores comunes

**Si el error es `ssh: no key found` o `handshake failed`:**
Significa que la llave privada se copió mal o el Paso 1 no quedó bien hecho. Revisa el secret `SSH_PRIVATE_KEY` en GitHub Settings.

**¿Cómo reintentar sin tocar el código?**

```bash
git commit --allow-empty -m "Reintentar despliegue"
git push origin main
```

También se puede entrar al error en GitHub y presionar **Re-run all jobs**.

---

### 3. Verificación final

Abrir el navegador y entrar a:

```
http://TU_IP_DEL_SERVIDOR:8000/docs
```

Si aparece la documentación de la API de FastAPI, el despliegue automático está funcionando correctamente.

