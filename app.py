from fastapi import FastAPI, HTTPException, status, Depends, Request, BackgroundTasks
from fastapi.middleware.cors import CORSMiddleware
from fastapi.templating import Jinja2Templates
from fastapi.responses import HTMLResponse
from fastapi.staticfiles import StaticFiles
from sqlalchemy.orm import Session
from datetime import date, datetime, timedelta
from math import radians, sin, cos, sqrt, atan2
from pydantic import BaseModel
import socket
import os

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
CLASES_EN_CURSO: dict = {}

from models.almacen import miClaseBase, abrir_puerta_bd, motor
from models.security_guard import (
    RevisarDatos, CrearCliente, CrearMateria, CrearEstudiante, MarcarAsistencia,
    ConfigurarUbicacionAula
)

# IMPORTANTE: Asegúrate de que estas tres funciones existan en archivo_seguridad.py
from models.archivo_seguridad import emitir_credencial, revisar_credencial_en_sistema, encriptar_contrasena, verificar_contrasena
from models.tablas import (
    TablaUsuarios, TablaClientes, TablaMaterias, TablaEstudiantes,
    TablaAsistencia, TablaGrabaciones
)
from models.sesion_qr import generar_nuevo_token, token_es_valido, SESIONES_QR_ACTIVAS
from models.correo import enviar_correo_grabacion

app = FastAPI()

templates = Jinja2Templates(directory="templates")
app.mount("/static", StaticFiles(directory="static"))

# ============================================================
# CONFIGURACION DE SEGURIDAD CORS PARA PRODUCCION
# ============================================================
origenes_permitidos = [
    "https:// cualquera.com", # lo reemplzao despues
    "http://localhost:5500",           # Para pruebas locales
    "http://127.0.0.1:5500",
    "http://localhost:8000",
    "http://127.0.0.1:8000"
]

app.add_middleware(
    CORSMiddleware,
    allow_origins=origenes_permitidos,
    allow_credentials=True,
    allow_methods=["GET", "POST", "PUT", "DELETE"],
    allow_headers=["*"],
)

miClaseBase.metadata.create_all(bind=motor)


# ============================================================
# RUTAS HTML — templating
# ============================================================

@app.get('/', response_class=HTMLResponse)
def home(request: Request):
    return templates.TemplateResponse(request, 'home.html')

@app.get('/iniciar_sesion', response_class=HTMLResponse)
def mostrar_login(request: Request):
    return templates.TemplateResponse(request, 'login.html')

@app.get('/sign_up', response_class=HTMLResponse)
def mostrar_signup(request: Request):
    return templates.TemplateResponse(request, 'signup.html')

@app.get('/interface', response_class=HTMLResponse)
def mostrar_interface(request: Request):
    return templates.TemplateResponse(request, 'interface.html')

@app.get('/workspace', response_class=HTMLResponse)
def mostrar_workspace(request: Request):
    return templates.TemplateResponse(request, 'workspace.html')



@app.post('/login', status_code=status.HTTP_200_OK)
def login(
    json_recibido: RevisarDatos,
    base_datos: Session = Depends(abrir_puerta_bd)
):
    user_que_vino = base_datos.query(TablaUsuarios).filter(
        TablaUsuarios.usuario == json_recibido.usuario
    ).first()

    if user_que_vino is None:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Usuario no encontrado"
        )


    if not verificar_contrasena(json_recibido.contrasena, user_que_vino.contrasena):
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Contrasena incorrecta!"
        )

    diccionario_profesor = {
        "id_usuario": user_que_vino.id_usuario,
        "usuario": user_que_vino.usuario
    }

    mi_token = emitir_credencial(diccionario_profesor)

    return {
        "mensaje": "Bienvenido",
        "token": mi_token,
        "token_type": "bearer"
    }


@app.post('/sign_up', status_code=status.HTTP_200_OK)
def crear_cliente(
    json_enviado: CrearCliente,
    base_datos: Session = Depends(abrir_puerta_bd)
):
    usuario_existente = base_datos.query(TablaUsuarios).filter(
        TablaUsuarios.usuario == json_enviado.usuario
    ).first()

    if usuario_existente is not None:
        raise HTTPException(
            status_code=status.HTTP_409_CONFLICT,
            detail=f"Usuario {json_enviado.usuario} ya esta siendo utilizado."
        )

   
    contrasena_segura = encriptar_contrasena(json_enviado.contrasena)

    nuevo_usuario = TablaUsuarios(
        usuario=json_enviado.usuario,
        contrasena=contrasena_segura
    )
    base_datos.add(nuevo_usuario)
    base_datos.flush()

    nuevo_cliente = TablaClientes(
        nombre=json_enviado.nombre,
        telefono=json_enviado.telefono,
        correo=json_enviado.correo,
        id_usuario=nuevo_usuario.id_usuario
    )
    base_datos.add(nuevo_cliente)
    base_datos.commit()

    diccionario_profesor = {
        "id_usuario": nuevo_usuario.id_usuario,
        "usuario": nuevo_usuario.usuario
    }
    mi_token = emitir_credencial(diccionario_profesor)

    return {
        "mensaje": f"Bienvenido {json_enviado.usuario}!",
        "token": mi_token,
        "token_type": "bearer"
    }


# ============================================================
# RUTAS REST — CRUD de materias
# ============================================================

@app.post("/crear_materia", status_code=status.HTTP_200_OK)
def crear_materia(
    json_enviado: CrearMateria,
    id_del_profesor: int = Depends(revisar_credencial_en_sistema),
    base_datos: Session = Depends(abrir_puerta_bd)
):
    datos_enviados = base_datos.query(TablaMaterias).filter(
        TablaMaterias.seccion == json_enviado.seccion
    ).first()

    if datos_enviados is not None:
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail=f"La seccion {datos_enviados.seccion} ya esta registrada."
        )

    nueva_clase = TablaMaterias(
        nombre=json_enviado.nombre,
        seccion=json_enviado.seccion,
        horario=json_enviado.horario,
        id_usuario=id_del_profesor
    )
    base_datos.add(nueva_clase)
    base_datos.commit()

    return {"mensaje": f"{json_enviado.nombre} ha sido creada con exito!"}


@app.get("/mis_materias")
def obtener_mis_materias(
    id_del_profesor: int = Depends(revisar_credencial_en_sistema),
    base_datos: Session = Depends(abrir_puerta_bd)
):
    materias_del_profe = base_datos.query(TablaMaterias).filter(
        TablaMaterias.id_usuario == id_del_profesor
    ).all()
    return materias_del_profe


# ============================================================
# RUTAS HTML + REST — inscripcion publica de estudiantes
# ============================================================

@app.get('/inscripcion', response_class=HTMLResponse)
def form_inscripcion_base(request: Request):
    return templates.TemplateResponse(request, 'inscripcion.html', {"seccion": ""})


@app.get('/inscribirse/{seccion}', response_class=HTMLResponse)
def form_inscripcion(seccion: str, request: Request):
    return templates.TemplateResponse(request, 'inscripcion.html', {"seccion": seccion})


@app.post('/inscribirse/{seccion}', status_code=status.HTTP_200_OK)
def guardar_inscripcion(
    seccion: str,
    json_enviado: CrearEstudiante,
    base_datos: Session = Depends(abrir_puerta_bd)
):
    materia_encontrada = base_datos.query(TablaMaterias).filter(
        TablaMaterias.seccion == seccion
    ).first()

    if materia_encontrada is None:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail=f"La seccion {seccion} no existe."
        )

    cuenta_existente = base_datos.query(TablaEstudiantes).filter(
        TablaEstudiantes.numero_cuenta == json_enviado.numero_cuenta,
        TablaEstudiantes.id_materia == materia_encontrada.id_materia
    ).first()

    if cuenta_existente is not None:
        raise HTTPException(
            status_code=status.HTTP_409_CONFLICT,
            detail=f"El numero de cuenta {json_enviado.numero_cuenta} ya esta registrado en esta clase."
        )

    modalidad_limpia = json_enviado.modalidad.strip().lower()
    if modalidad_limpia not in ("presencial", "virtual"):
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail="Modalidad debe ser 'presencial' o 'virtual'."
        )

    nuevo_estudiante = TablaEstudiantes(
        nombre=json_enviado.nombre,
        telefono=json_enviado.telefono,
        correo=json_enviado.correo,
        numero_cuenta=json_enviado.numero_cuenta,
        modalidad=modalidad_limpia,
        id_materia=materia_encontrada.id_materia
    )
    base_datos.add(nuevo_estudiante)
    base_datos.commit()

    return {
        "mensaje": f"Listo {json_enviado.nombre}! Quedaste inscrito en {materia_encontrada.nombre}."
    }


@app.get('/materia/{id_materia}/link_inscripcion')
def obtener_link_inscripcion(
    id_materia: int,
    id_del_profesor: int = Depends(revisar_credencial_en_sistema),
    base_datos: Session = Depends(abrir_puerta_bd)
):
    materia = base_datos.query(TablaMaterias).filter(
        TablaMaterias.id_materia == id_materia,
        TablaMaterias.id_usuario == id_del_profesor
    ).first()

    if materia is None:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail="Esa materia no existe o no te pertenece."
        )

    return {"seccion": materia.seccion, "ruta": f"/inscribirse/{materia.seccion}"}


@app.post("/materia/{id_materia}/configurar_ubicacion")
def configurar_ubicacion_aula(
    id_materia: int,
    json_enviado: ConfigurarUbicacionAula,
    id_del_profesor: int = Depends(revisar_credencial_en_sistema),
    base_datos: Session = Depends(abrir_puerta_bd)
):
    materia = base_datos.query(TablaMaterias).filter(
        TablaMaterias.id_materia == id_materia,
        TablaMaterias.id_usuario == id_del_profesor
    ).first()

    if materia is None:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail="Esa materia no existe o no te pertenece."
        )

    materia.lat_aula = str(json_enviado.lat)
    materia.lng_aula = str(json_enviado.lng)
    base_datos.commit()

    return {"mensaje": "Ubicacion del aula guardada correctamente."}


# ============================================================
# RUTAS REST — QR y asistencia
# ============================================================

@app.get("/materia/{id_materia}/generar_qr_token")
def generar_qr_token(
    id_materia: int,
    id_del_profesor: int = Depends(revisar_credencial_en_sistema),
    base_datos: Session = Depends(abrir_puerta_bd)
):
    materia = base_datos.query(TablaMaterias).filter(
        TablaMaterias.id_materia == id_materia,
        TablaMaterias.id_usuario == id_del_profesor
    ).first()

    if materia is None:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail="Esa materia no existe o no te pertenece."
        )

    if id_materia not in CLASES_EN_CURSO:
        CLASES_EN_CURSO[id_materia] = datetime.utcnow()

    resultado = generar_nuevo_token(id_materia)
    resultado["base_url"] = BASE_URL
    return resultado


@app.get("/qr_pantalla/{id_materia}", response_class=HTMLResponse)
def qr_pantalla(id_materia: int, request: Request):
    return templates.TemplateResponse(
        request,
        "qr_pantalla.html",
        {"id_materia": id_materia, "base_url": BASE_URL}
    )


@app.get("/materia/{id_materia}/generar_qr_token_publico")
def generar_qr_token_publico(id_materia: int):
    if id_materia not in SESIONES_QR_ACTIVAS and id_materia not in CLASES_EN_CURSO:
        raise HTTPException(
            status_code=status.HTTP_403_FORBIDDEN,
            detail="No hay una clase activa para esta materia."
        )
    resultado = generar_nuevo_token(id_materia)
    resultado["base_url"] = BASE_URL
    return resultado


@app.get("/materia/{id_materia}/clase_en_curso")
def clase_en_curso(
    id_materia: int,
    id_del_profesor: int = Depends(revisar_credencial_en_sistema)
):
    inicio = CLASES_EN_CURSO.get(id_materia)
    if inicio is None:
        return {"en_curso": False}
    if datetime.utcnow() - inicio > timedelta(hours=3):
        del CLASES_EN_CURSO[id_materia]
        return {"en_curso": False}
    return {"en_curso": True}


@app.get("/marcar", response_class=HTMLResponse)
def form_marcar_asistencia(id_materia: int, token: str, request: Request):
    return templates.TemplateResponse(
        request,
        "marcar_asistencia.html",
        {"id_materia": id_materia, "token": token}
    )


def distancia_metros(lat1, lng1, lat2, lng2) -> float:
    R = 6371000
    dlat = radians(lat2 - lat1)
    dlng = radians(lng2 - lng1)
    a = sin(dlat / 2) ** 2 + cos(radians(lat1)) * cos(radians(lat2)) * sin(dlng / 2) ** 2
    return R * 2 * atan2(sqrt(a), sqrt(1 - a))


@app.post("/marcar_asistencia")
def marcar_asistencia(
    id_materia: int,
    json_enviado: MarcarAsistencia,
    base_datos: Session = Depends(abrir_puerta_bd)
):
    if not token_es_valido(id_materia, json_enviado.token):
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="El codigo QR ya expiro. Pide al profesor que muestre uno nuevo."
        )

    estudiante = base_datos.query(TablaEstudiantes).filter(
        TablaEstudiantes.numero_cuenta == json_enviado.numero_cuenta,
        TablaEstudiantes.id_materia == id_materia
    ).first()

    if estudiante is None:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail="Tu numero de cuenta no esta inscrito en esta clase."
        )

    materia = base_datos.query(TablaMaterias).filter(
        TablaMaterias.id_materia == id_materia
    ).first()

    dentro_del_rango = None

    if estudiante.modalidad == "presencial" and materia.lat_aula and materia.lng_aula:
        if json_enviado.lat is not None and json_enviado.lng is not None:
            distancia = distancia_metros(
                json_enviado.lat, json_enviado.lng,
                float(materia.lat_aula), float(materia.lng_aula)
            )
            dentro_del_rango = distancia <= 50

    hoy = date.today()
    ya_marco = base_datos.query(TablaAsistencia).filter(
        TablaAsistencia.id_estudiante == estudiante.id_estudiante,
        TablaAsistencia.fecha == hoy
    ).first()

    if ya_marco is not None:
        raise HTTPException(
            status_code=status.HTTP_409_CONFLICT,
            detail="Ya marcaste tu asistencia hoy."
        )

    nueva_asistencia = TablaAsistencia(
        id_estudiante=estudiante.id_estudiante,
        id_materia=id_materia,
        fecha=hoy,
        presente=True,
        modalidad_usada=estudiante.modalidad,
        dentro_del_rango=dentro_del_rango
    )
    base_datos.add(nueva_asistencia)
    base_datos.commit()

    return {"mensaje": f"Asistencia registrada, {estudiante.nombre}!"}


@app.get("/materia/{id_materia}/asistencia_hoy")
def obtener_asistencia_hoy(
    id_materia: int,
    id_del_profesor: int = Depends(revisar_credencial_en_sistema),
    base_datos: Session = Depends(abrir_puerta_bd)
):
    materia = base_datos.query(TablaMaterias).filter(
        TablaMaterias.id_materia == id_materia,
        TablaMaterias.id_usuario == id_del_profesor
    ).first()

    if materia is None:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail="Esa materia no existe o no te pertenece."
        )

    hoy = date.today()
    todos_los_estudiantes = base_datos.query(TablaEstudiantes).filter(
        TablaEstudiantes.id_materia == id_materia
    ).all()

    registros_de_hoy = {
        a.id_estudiante: a for a in base_datos.query(TablaAsistencia).filter(
            TablaAsistencia.id_materia == id_materia,
            TablaAsistencia.fecha == hoy,
            TablaAsistencia.presente == True
        ).all()
    }

    presentes = [e for e in todos_los_estudiantes if e.id_estudiante in registros_de_hoy]
    ausentes = [e for e in todos_los_estudiantes if e.id_estudiante not in registros_de_hoy]

    return {
        "presentes": [
            {
                "nombre": e.nombre,
                "modalidad": e.modalidad,
                "dentro_del_rango": registros_de_hoy[e.id_estudiante].dentro_del_rango
            }
            for e in presentes
        ],
        "ausentes": [{"nombre": e.nombre, "modalidad": e.modalidad} for e in ausentes]
    }


# ============================================================
# RUTAS REST — CRUD completo de estudiantes
# ============================================================

@app.get("/materia/{id_materia}/estudiantes")
def listar_estudiantes_con_faltas(
    id_materia: int,
    id_del_profesor: int = Depends(revisar_credencial_en_sistema),
    base_datos: Session = Depends(abrir_puerta_bd)
):
    materia = base_datos.query(TablaMaterias).filter(
        TablaMaterias.id_materia == id_materia,
        TablaMaterias.id_usuario == id_del_profesor
    ).first()

    if materia is None:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail="Esa materia no existe o no te pertenece."
        )

    estudiantes = base_datos.query(TablaEstudiantes).filter(
        TablaEstudiantes.id_materia == id_materia
    ).all()

    resultado = []
    for est in estudiantes:
        total_faltas = base_datos.query(TablaAsistencia).filter(
            TablaAsistencia.id_estudiante == est.id_estudiante,
            TablaAsistencia.presente == False
        ).count()

        resultado.append({
            "id_estudiante": est.id_estudiante,
            "nombre": est.nombre,
            "correo": est.correo,
            "telefono": est.telefono,
            "numero_cuenta": est.numero_cuenta,
            "modalidad": est.modalidad,
            "faltas": total_faltas
        })

    return resultado


@app.post("/materia/{id_materia}/agregar_estudiante", status_code=status.HTTP_200_OK)
def agregar_estudiante_manual(
    id_materia: int,
    json_enviado: CrearEstudiante,
    id_del_profesor: int = Depends(revisar_credencial_en_sistema),
    base_datos: Session = Depends(abrir_puerta_bd)
):
    materia = base_datos.query(TablaMaterias).filter(
        TablaMaterias.id_materia == id_materia,
        TablaMaterias.id_usuario == id_del_profesor
    ).first()

    if materia is None:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail="Esa materia no existe o no te pertenece."
        )

    cuenta_existente = base_datos.query(TablaEstudiantes).filter(
        TablaEstudiantes.numero_cuenta == json_enviado.numero_cuenta,
        TablaEstudiantes.id_materia == id_materia
    ).first()

    if cuenta_existente is not None:
        raise HTTPException(
            status_code=status.HTTP_409_CONFLICT,
            detail=f"El numero de cuenta {json_enviado.numero_cuenta} ya esta registrado en esta clase."
        )

    modalidad_limpia = json_enviado.modalidad.strip().lower()
    if modalidad_limpia not in ("presencial", "virtual"):
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail="Modalidad debe ser 'presencial' o 'virtual'."
        )

    nuevo_estudiante = TablaEstudiantes(
        nombre=json_enviado.nombre,
        telefono=json_enviado.telefono,
        correo=json_enviado.correo,
        numero_cuenta=json_enviado.numero_cuenta,
        modalidad=modalidad_limpia,
        id_materia=id_materia
    )
    base_datos.add(nuevo_estudiante)
    base_datos.commit()

    return {"mensaje": f"{json_enviado.nombre} agregado correctamente."}


@app.put("/estudiante/{id_estudiante}")
def editar_estudiante(
    id_estudiante: int,
    json_enviado: CrearEstudiante,
    id_del_profesor: int = Depends(revisar_credencial_en_sistema),
    base_datos: Session = Depends(abrir_puerta_bd)
):
    estudiante = base_datos.query(TablaEstudiantes).filter(
        TablaEstudiantes.id_estudiante == id_estudiante
    ).first()

    if estudiante is None:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail="Ese estudiante no existe."
        )

    materia = base_datos.query(TablaMaterias).filter(
        TablaMaterias.id_materia == estudiante.id_materia,
        TablaMaterias.id_usuario == id_del_profesor
    ).first()

    if materia is None:
        raise HTTPException(
            status_code=status.HTTP_403_FORBIDDEN,
            detail="Ese estudiante no pertenece a una de tus materias."
        )

    estudiante.nombre = json_enviado.nombre
    estudiante.telefono = json_enviado.telefono
    estudiante.correo = json_enviado.correo
    estudiante.numero_cuenta = json_enviado.numero_cuenta
    estudiante.modalidad = json_enviado.modalidad.strip().lower()
    base_datos.commit()

    return {"mensaje": f"{estudiante.nombre} actualizado correctamente."}


@app.delete("/estudiante/{id_estudiante}")
def eliminar_estudiante(
    id_estudiante: int,
    id_del_profesor: int = Depends(revisar_credencial_en_sistema),
    base_datos: Session = Depends(abrir_puerta_bd)
):
    estudiante = base_datos.query(TablaEstudiantes).filter(
        TablaEstudiantes.id_estudiante == id_estudiante
    ).first()

    if estudiante is None:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail="Ese estudiante no existe."
        )

    materia = base_datos.query(TablaMaterias).filter(
        TablaMaterias.id_materia == estudiante.id_materia,
        TablaMaterias.id_usuario == id_del_profesor
    ).first()

    if materia is None:
        raise HTTPException(
            status_code=status.HTTP_403_FORBIDDEN,
            detail="Ese estudiante no pertenece a una de tus materias."
        )

    base_datos.query(TablaAsistencia).filter(
        TablaAsistencia.id_estudiante == id_estudiante
    ).delete()

    base_datos.delete(estudiante)
    base_datos.commit()

    return {"mensaje": "Estudiante eliminado del curso."}


class UrlGrabacion(BaseModel):
    url: str

@app.post("/materia/{id_materia}/publicar_grabacion")
def publicar_grabacion(
    id_materia: int,
    payload: UrlGrabacion,
    background_tasks: BackgroundTasks,
    id_del_profesor: int = Depends(revisar_credencial_en_sistema),
    base_datos: Session = Depends(abrir_puerta_bd)
):
    materia = base_datos.query(TablaMaterias).filter(
        TablaMaterias.id_materia == id_materia,
        TablaMaterias.id_usuario == id_del_profesor
    ).first()

    if materia is None:
        raise HTTPException(
            status_code=status.HTTP_403_FORBIDDEN,
            detail="Esa materia no te pertenece."
        )

    nueva_grabacion = TablaGrabaciones(
        id_materia=id_materia,
        url_video=payload.url,
        fecha_publicacion=datetime.utcnow()
    )
    base_datos.add(nueva_grabacion)
    base_datos.commit()

    estudiantes = base_datos.query(TablaEstudiantes).filter(
        TablaEstudiantes.id_materia == id_materia
    ).all()
    lista_correos = [e.correo for e in estudiantes]

    background_tasks.add_task(
        enviar_correo_grabacion, lista_correos, materia.nombre, payload.url
    )

    return {
        "mensaje": f"Grabacion publicada. Enviando correo a {len(lista_correos)} estudiante(s)."
    }