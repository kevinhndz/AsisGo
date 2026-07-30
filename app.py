from fastapi import FastAPI, HTTPException, status, Depends, Request
from fastapi.middleware.cors import CORSMiddleware
from fastapi.templating import Jinja2Templates
from fastapi.responses import HTMLResponse
from fastapi.staticfiles import StaticFiles

from models.almacen import miClaseBase,abrir_puerta_bd,motor
from models.security_guard import BaseModel, RevisarDatos, CrearCliente, CrearMateria, CrearEstudiante
from models.tablas import TablaUsuarios, TablaClientes, TablaMaterias, TablaEstudiantes
from sqlalchemy.orm import Session
from models.archivo_seguridad import emitir_credencial, revisar_credencial_en_sistema




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
    
    #Aqui empieza la Autenticacion
    
    #Step 1 --> Cree un hashmap para mandar a crear un nuevo Token
    diccionario_profesor = {
        "id_usuario":user_que_vino.id_usuario,
        "usuario": user_que_vino.usuario
    }
    
    #Step #2 --> mandar a llamr emituir credecial para mandar el JSON a revision
    #PSDTA: Esta funcion lo que hace es devolverme mi JWT secreto.
    mi_token = emitir_credencial(diccionario_profesor)
    
    #
    return {
        
        "mensaje":"Bienvenido",
        "token":mi_token,  # aqui lo agrego
        "token_type":"bearer"
    
    }
    
    
    
    
    
        
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
       
        # Creamos su token de bienvenida al registrarse
        diccionario_profesor = {
            "id_usuario": nuevo_usuario.id_usuario,
            "usuario": nuevo_usuario.usuario
        }
        mi_token = emitir_credencial(diccionario_profesor)
        
        # Devolvemos el token de forma limpia
        return {
            "mensaje": f" ¡Bienvenido {json_enviado.usuario}!",
            "token": mi_token,
            "token_type": "bearer"
        }
        
        
"""

recibimos un JSON - > ese JSON trae el mismo JSON en texto plano (como siempre) 
 Y ADEMAS vine con el token en el header authorization 
# entonces que pasa? le pasamos una variable llamada - > id_del_profesor en el cual guardaremos el id 
de ese profesor que esta logueado que aun no sabemos!
Entonces como todo codigo en python se lee de izquierda a derecha ejecutamos la funcion  revisar_cred_sistema
y nos movemos al otro modulo


"""
@app.post("/crear_materia", status_code= status.HTTP_200_OK)
def crear_materia (
     json_enviado : CrearMateria,
     id_del_profesor: int = Depends(revisar_credencial_en_sistema),
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
            horario = json_enviado.horario,
            id_usuario=id_del_profesor # lo agregamos para que sepamos de que clase es quien
            )
        base_datos.add(nueva_clase)
        base_datos.commit()
        
        return {"mensaje": f"{json_enviado.nombre} ha sido creada con exito!"}
    
    
@app.get("/mis_materias")
def obtener_mis_materias(
    id_del_profesor: int = Depends(revisar_credencial_en_sistema),
    base_datos: Session = Depends(abrir_puerta_bd)
):
    materias_del_profe = base_datos.query(TablaMaterias).filter(TablaMaterias.id_usuario == id_del_profesor).all()
    return materias_del_profe
    

@app.get('/inscribirse/{seccion}', response_class=HTMLResponse)
def form_inscripcion(seccion: str, request: Request):
    
    return templates.TemplateResponse(
        request,
        'inscripcion.html',
        {"seccion": seccion}
    )


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
            detail=f"La seccion {seccion} no existe. Revisa el link con tu profesor."
        )


    cuenta_existente = base_datos.query(TablaEstudiantes).filter(
        TablaEstudiantes.numero_cuenta == json_enviado.numero_cuenta
    ).first()

    if cuenta_existente is not None:
        raise HTTPException(
            status_code=status.HTTP_409_CONFLICT,
            detail=f"El numero de cuenta {json_enviado.numero_cuenta} ya esta registrado."
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
        "mensaje": f"¡Listo {json_enviado.nombre}! Quedaste inscrito en {materia_encontrada.nombre}."
    }


@app.get('/materia/{id_materia}/link_inscripcion')
def obtener_link_inscripcion(
    id_materia: int,
    id_del_profesor: int = Depends(revisar_credencial_en_sistema),
    base_datos: Session = Depends(abrir_puerta_bd)
):
    materia = base_datos.query(TablaMaterias).filter(
        TablaMaterias.id_materia == id_materia,
        TablaMaterias.id_usuario == id_del_profesor  # <-- solo si es SU materia
    ).first()

    if materia is None:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail="Esa materia no existe o no te pertenece."
        )

  
    return {"seccion": materia.seccion, "ruta": f"/inscribirse/{materia.seccion}"}