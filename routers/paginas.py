from fastapi import APIRouter, Request, Response
from fastapi.responses import HTMLResponse
from fastapi.templating import Jinja2Templates


router = APIRouter()
templates = Jinja2Templates(directory="templates")


@router.get("/", response_class=HTMLResponse)
def home(request: Request):
    return templates.TemplateResponse(request, "home.html")


@router.get("/iniciar_sesion", response_class=HTMLResponse)
def mostrar_login(request: Request):
    return templates.TemplateResponse(request, "login.html")


@router.get("/sign_up", response_class=HTMLResponse)
def mostrar_signup(request: Request):
    return templates.TemplateResponse(request, "signup.html")


def _respuesta_sin_cache(response: Response) -> None:
    response.headers["Cache-Control"] = "no-store, no-cache, must-revalidate, max-age=0"
    response.headers["Pragma"] = "no-cache"
    response.headers["Expires"] = "0"


@router.get("/interface", response_class=HTMLResponse)
def mostrar_interface(request: Request, response: Response):
    _respuesta_sin_cache(response)
    return templates.TemplateResponse(request, "interface.html")


@router.get("/workspace", response_class=HTMLResponse)
def mostrar_workspace(request: Request, response: Response):
    _respuesta_sin_cache(response)
    return templates.TemplateResponse(request, "workspace.html")
