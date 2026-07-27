from fastapi import FastAPI, HTTPException, status, Depends, Request
from fastapi.middleware.cors import CORSMiddleware
from fastapi.templating import Jinja2Templates
from fastapi.responses import HTMLResponse
from fastapi.staticfiles import StaticFiles

#model imports
from models.almacen import miClaseBase,abrir_puerta_bd,motor
from models.security_guard import BaseModel, RevisarDatos
from models.tablas import TablaUsuarios
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


@app.get('/', response_class=HTMLResponse)
def mostrar_login(request:Request):
    return templates.TemplateResponse(request, 'login.html')


@app.post('/login', status_code=status.HTTP_200_OK)
def login(
    json_recibido: RevisarDatos,
    base_datos: Session = Depends(abrir_puerta_bd)
):
    user_que_vino = base_datos.query(TablaUsuarios).filter(TablaUsuarios.usuario == json_recibido.usuario).first()
    
    if user_que_vino is None:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Usuario o contraseña incorrectos"
        )
    
    if user_que_vino.contrasena != json_recibido.contrasena:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Contrasena Incorrecta!"
        )
    
    return {"mensaje": f"{user_que_vino.usuario} Bienvenido!"}
    
    
        
    
    
    
    