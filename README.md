# AsisGo

Sistema de inscripción y control de asistencia para clases. La aplicación utiliza Laravel y PHP con PostgreSQL/Supabase; Blade presenta las páginas y los archivos CSS y JavaScript se sirven por separado.

## Requisitos

- PHP 8.3 o superior con `dom`, `mbstring`, `pdo_pgsql` y `zip`.
- Composer 2.
- PostgreSQL o una base Supabase con las tablas existentes de AsisGo.

## Ejecutar localmente

```powershell
Copy-Item .env.example .env
composer install
php artisan key:generate
```

Configura `.env` con los datos del Session Pooler de Supabase (`DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) y define `JWT_SECRET` con la misma firma que usa el sistema actual. Configura también las credenciales SMTP si necesitas enviar grabaciones por correo.

```powershell
php artisan serve
```

El sistema usa las tablas existentes (`usuarios`, `clientes`, `materias`, `estudiantes`, `asistencia`, `grabaciones`); no crea tablas ni modifica el esquema de Supabase al arrancar.

## Estructura

- `app/Http/Controllers`: páginas, autenticación, materias, inscripciones, estudiantes y asistencia.
- `app/Models`: modelos Eloquent que conservan los nombres y columnas actuales de PostgreSQL.
- `app/Services`: emisión y comprobación de JWT, sesiones QR y cálculos de periodo/distancia.
- `routes/web.php`: páginas y contrato de endpoints JSON existentes.
- `resources/views/pagina.blade.php`: páginas del sitio en una plantilla Blade.
- `resources/views/reporte.blade.php`: diseño del informe PDF.
- `public/css` y `public/js`: estilos y comportamiento del navegador separados del HTML.

## Despliegue

La imagen de Docker usa PHP 8.3 con Apache y PostgreSQL, y escucha en el puerto 8000 para conservar el mapeo actual del servidor. El workflow de `main` valida dependencias y rutas, construye la imagen y después despliega por SSH.

En producción, conserva las variables existentes de base de datos y correo. `DB_USER`, `FIRMA_DEL_DIRECTOR`, `EMAIL_USER` y `EMAIL_PASSWORD` siguen siendo aceptadas; se recomienda migrarlas gradualmente a `DB_USERNAME`, `JWT_SECRET`, `MAIL_USERNAME` y `MAIL_PASSWORD`. El contenedor genera una clave Laravel al arrancar solo si no recibe `APP_KEY`; configura una `APP_KEY` persistente para conservar sesiones entre reinicios.
