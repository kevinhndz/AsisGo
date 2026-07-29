from fastapi import FastAPI, HTTPException, status, Depends, Request
from fastapi.middleware.cors import CORSMiddleware
from fastapi.templating import Jinja2Templates
from fastapi.responses import HTMLResponse
from fastapi.staticfiles import StaticFiles

from models.almacen import miClaseBase,abrir_puerta_bd,motor
from models.security_guard import BaseModel, RevisarDatos, CrearCliente, CrearMateria
from models.tablas import TablaUsuarios, TablaClientes, TablaMaterias
from sqlalchemy.orm import Session




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

@app.get('/workspace', response_class=HTMLResponse)
def mostrar_workspace(request: Request):
    return templates.TemplateResponse(request,'workspace.html')




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
            
            nuevo_cliente = TablaClientes(nombre=json_enviado.nombre, telefono=json_enviado.telefono, correo = json_enviado.correo, id_usuario=nuevo_usuario.id_usuario)
            base_datos.add(nuevo_cliente)
            base_datos.commit()
           
            
            return {"mensaje": f" ¡Bienvenido {json_enviado.usuario}!"}
        
        
@app.post("/crear_materia", status_code= status.HTTP_200_OK)
def crear_materia (
     json_enviado : CrearMateria,
     base_datos : Session = Depends(abrir_puerta_bd)
):
    datos_enviados = base_datos.query(TablaMaterias).filter(TablaMaterias.seccion == json_enviado.seccion).first()
    
    if datos_enviados is not None:
        raise HTTPException(
            status_code= status.HTTP_400_BAD_REQUEST,
            detail= f"La seccion: {datos_enviados.seccion} ya esta registrada."
        )
    else:
        nueva_clase = TablaMaterias(
            nombre = json_enviado.nombre, 
            seccion = json_enviado.seccion, 
            horario = json_enviado.horario
            )
        base_datos.add(nueva_clase)
        base_datos.commit()
        
    
        return  {"mensaje": f"{json_enviado.nombre} ha sido creada con exito!"}
    
    
  