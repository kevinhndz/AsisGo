# 🚀 Guía de como crear una  CI/CD: De Cero a Producción (Con Git-Hub Actions)

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