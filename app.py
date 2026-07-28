from fastapi import FastAPI, HTTPException, status, Depends, Request
from fastapi.middleware.cors import CORSMiddleware
from fastapi.templating import Jinja2Templates
from fastapi.responses import HTMLResponse
from fastapi.staticfiles import StaticFiles

from models.almacen import miClaseBase,abrir_puerta_bd,motor
from models.security_guard import BaseModel, RevisarDatos, CrearCliente, CrearClase, RegistrarEstudiante
from models.tablas import TablaUsuarios, TablaClientes, TablaClases, TablaAsistencia, TablaEstudiantes
from sqlalchemy.orm import Session

import pyotp
from io import BytesIO
import base64



app = FastAPI()
templates = Jinja2Templates(directory="templates")
app.mount("/static",StaticFiles(directory="static"))

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# sino existe creala
miClaseBase.metadata.create_all(bind = motor)


#RUTA templates

@app.get('/', response_class=HTMLResponse)
def home(request: Request):
    return templates.TemplateResponse(request,'home.html')

@app.get('/iniciar_sesion', response_class=HTMLResponse)
def mostrar_login(request:Request):
    return templates.TemplateResponse(request, 'login.html')

@app.get('/crear_cliente', response_class= HTMLResponse)
def mostrar_sign_up_page(request: Request):
    return templates.TemplateResponse(request,'signup.html')

@app.get('/interface', response_class=HTMLResponse)
def mostrar_interface(request: Request):
    return templates.TemplateResponse(request,'interface.html')




# RUTAS REST API

@app.post('/login', status_code=status.HTTP_200_OK)
def login(
    json_recibido: RevisarDatos,
    base_datos: Session = Depends(abrir_puerta_bd)
):
    user_que_vino = base_datos.query(TablaUsuarios).filter(TablaUsuarios.usuario == json_recibido.usuario).first()
    
    if user_que_vino is None:
        # BUFIX (Error #1): pedian que si el usuario no existe diga
        # "Usuario no encontrado" en vez de "Usuario o contraseña incorrectos"
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Usuario no encontrado"
        )
    
    if user_que_vino.contrasena != json_recibido.contrasena:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Contrasena Incorrecta!"
        )
    
    return {"mensaje": f"{user_que_vino.usuario} Bienvenido!"}
    
    
        
@app.post('/sign_up', status_code=status.HTTP_200_OK)
def crear_cliente(
     json_enviado: CrearCliente,
     base_datos: Session = Depends(abrir_puerta_bd)
):
    usuario_enviado = base_datos.query(TablaUsuarios).filter(TablaUsuarios.usuario == json_enviado.usuario).first()
    
    if usuario_enviado is not None:
        raise HTTPException(
            status_code=status.HTTP_409_CONFLICT,
            detail=f"Usuario: {json_enviado.usuario} ya esta siendo utilizado. Crea otro! "
        )
    else:
        
            nuevo_usuario = TablaUsuarios(usuario=json_enviado.usuario, contrasena=json_enviado.contrasena)
            base_datos.add(nuevo_usuario)
            base_datos.flush()
            
            nuevo_cliente = TablaClientes(nombre=json_enviado.nombre, telefono=json_enviado.telefono, id_usuario=nuevo_usuario.id_usuario)
            base_datos.add(nuevo_cliente)
            base_datos.commit()
           
            
            return {"mensaje": f" ¡Bienvenido {json_enviado.usuario}!"}


@app.post('/crear-clase', status_code=status.HTTP_200_OK)
def crear_clase(
    json_clase: CrearClase,
    base_datos: Session = Depends(abrir_puerta_bd)
):
    clase_existe = base_datos.query(TablaClases).filter(TablaClases.codigo == json_clase.codigo).first()
    if clase_existe:
        raise HTTPException(status_code=409, detail="Código de clase ya existe")
    
    nueva_clase = TablaClases(
        nombre=json_clase.nombre,
        codigo=json_clase.codigo,
        horario=json_clase.horario,
        ubicacion=json_clase.ubicacion,
        id_profesor=1  # Por ahora fijo
    )
    base_datos.add(nueva_clase)
    base_datos.commit()
    return {"mensaje": f"Clase {json_clase.nombre} creada", "id": nueva_clase.id_clase}


@app.get('/mis-clases')
def obtener_mis_clases(base_datos: Session = Depends(abrir_puerta_bd)):
    clases = base_datos.query(TablaClases).filter(TablaClases.id_profesor == 1).all()
    return [{"id": c.id_clase, "nombre": c.nombre, "codigo": c.codigo, "horario": c.horario, "ubicacion": c.ubicacion} for c in clases]


@app.get('/clase/{id_clase}')
def obtener_clase(id_clase: int, base_datos: Session = Depends(abrir_puerta_bd)):
    clase = base_datos.query(TablaClases).filter(TablaClases.id_clase == id_clase).first()
    if not clase:
        raise HTTPException(status_code=404, detail="Clase no encontrada")
    
    estudiantes = base_datos.query(TablaEstudiantes).filter(TablaEstudiantes.id_clase == id_clase).all()
    return {
        "id": clase.id_clase,
        "nombre": clase.nombre,
        "codigo": clase.codigo,
        "horario": clase.horario,
        "ubicacion": clase.ubicacion,
        "estudiantes": [{"id": e.id_estudiante, "nombre": e.nombre, "numero_cuenta": e.numero_cuenta, "modalidad": e.modalidad} for e in estudiantes]
    }