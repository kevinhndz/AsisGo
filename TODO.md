# Fix Plan — Asis GO+

## Priority 1: Security (Password Hashing)

- [ ] Install bcrypt/passlib dependency
- [ ] models/tablas.py: Increase `contrasena` to String(255)
- [ ] app.py: Hash password on signup, verify on login

## Priority 2: Model Fixes

- [ ] models/almacen.py: Fix typo `RUTA_ALMACEN` → `DATABASE_URL`, `FabricaLLaves` → `SessionLocal`
- [ ] models/tablas.py: Change `lat_aula`/`lng_aula` to Float
- [ ] models/security_guard.py: Increase password max_length to 128
- [ ] models/archivo_seguridad.py: Validate FIRMA_DEL_DIRECTOR, rename `fin_del_semestre`

## Priority 3: Bug Fixes

- [ ] app.py: Add `load_dotenv()`, fix BUFIX → BUGFIX, fix status code 201 for signup
- [ ] models/sesion_qr.py: Invalidate QR token after use

## Priority 4: Template Fixes

- [ ] templates/login.html: Fix title, use relative URLs
- [ ] templates/inscripcion.html: Use Jinja2 variable
- [ ] templates/interface.html: Use relative URLs

## Priority 5: Cleanup

- [ ] requirements.txt: Remove Flask, add passlib, python-dotenv
- [ ] Commit to GitHub main branch
