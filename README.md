# 🚀 Guía de Despliegue Backend (FastAPI + SQL)

Esta es una lista de verificación estricta para asegurar que una API está lista para salir a producción, prevenir hackeos, fugas de datos o caídas del servidor.

## 🔐 1. Seguridad de Credenciales y Datos
- [ ] **Hash de contraseñas:** Ninguna contraseña debe guardarse en texto plano. Usar `passlib` (bcrypt o argon2).
- [ ] **Secretos Fuertes (JWT):** La firma del JWT (`SECRET_KEY`) debe ser una cadena aleatoria de al menos 32 caracteres generada criptográficamente (ej. `openssl rand -hex 32`).


## 🏗️ 2. Arquitectura Multitenant (Aislamiento de Usuarios)
- [ ] **Validación de Propiedad:** Todo endpoint que realice un `GET`, `PUT` o `DELETE` por ID (`/recurso/{id}`) debe incluir un filtro `WHERE id_usuario = {usuario_en_sesion}` para evitar que el Usuario A modifique los datos del Usuario B.

## 🚦 3. Rendimiento y Estabilidad
- [ ] **Conexiones a Base de Datos:** SQLAlchemy debe usar `pool_size`, `max_overflow`, y `pool_pre_ping=True` para evitar caídas si la base de datos desconecta al servidor por inactividad.
- [ ] **Tareas Asíncronas (Background Tasks):** Envío de correos electrónicos, generación de PDFs pesados o procesamiento de imágenes deben ir en `BackgroundTasks` o Celery para no bloquear el Hilo HTTP.

## 🛡️ 4. Protección de Red y HTTP
- [ ] **CORS Estricto:** Reemplazar `allow_origins=["*"]` por los dominios reales del frontend.
- [ ] **HTTPS:** Asegurar que el servidor de despliegue tenga un certificado SSL/TLS activo (ofrecido por Render, Railway, AWS o mediante Nginx/Certbot).
- [ ] **Workers:** Iniciar el servidor usando múltiples hilos. En lugar de `uvicorn main:app`, usar un comando para producción: `uvicorn main:app --host 0.0.0.0 --port 8000 --workers 4`.