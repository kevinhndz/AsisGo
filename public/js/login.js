
function togglePass() {
  const inp = document.getElementById('contrasena');
  const ico = document.getElementById('eye-icon');
  inp.type = inp.type === 'password' ? 'text' : 'password';
  ico.textContent = inp.type === 'password' ? 'visibility' : 'visibility_off';
}

async function handleLogin(e) {
  e.preventDefault();
  const btn    = document.getElementById('btn-submit');
  const txt    = document.getElementById('btn-text');
  const ico    = document.getElementById('btn-icon');
  const loader = document.getElementById('btn-loader');
  const msgEl  = document.getElementById('msg-error');

  btn.classList.add('pointer-events-none');
  txt.style.transform = 'translateY(20px)';
  ico.style.transform = 'translateY(20px)';
  loader.classList.remove('hidden');
  msgEl.innerText = '';

  // BUG #2: try/catch global para que un error de red no rompa la UI
  try {
    const resp = await fetch('/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        usuario:    document.getElementById('usuario').value.trim(),
        contrasena: document.getElementById('contrasena').value
      })
    });

    let datos = {};
    try { datos = await resp.json(); } catch (_) {}

    loader.classList.add('hidden');
    txt.style.transform = 'translateY(0)';
    ico.style.transform = 'translateY(0)';

    if (resp.ok) {
      sessionStorage.setItem('miTokenJWT', datos.token);
      txt.innerText = '¡Bienvenido!';
      ico.textContent = 'check_circle';
      setTimeout(() => window.location.href = '/workspace', 700);
    } else {
     
      let mensajeError;
      if (typeof datos.detail === 'string') {
        mensajeError = datos.detail;
      } else if (Array.isArray(datos.detail)) {
        // Pydantic v2: array de objetos de validación → unimos los msg
        mensajeError = datos.detail.map(err => err.msg || JSON.stringify(err)).join(', ');
      } else if (resp.status === 401) {
        mensajeError = 'Usuario o contraseña incorrectos.';
      } else if (resp.status === 422) {
        mensajeError = 'Datos inválidos. El usuario debe tener entre 6 y 10 caracteres.';
      } else {
        mensajeError = 'Ocurrió un error inesperado. Intenta de nuevo.';
      }
      msgEl.innerText = mensajeError;
      btn.classList.remove('pointer-events-none');
    }

  } catch (errorRed) {
   
    loader.classList.add('hidden');
    txt.style.transform = 'translateY(0)';
    ico.style.transform = 'translateY(0)';
    msgEl.innerText = 'Error de conexión. Verifica tu internet e intenta de nuevo.';
    btn.classList.remove('pointer-events-none');
  }
}


(function mostrarAvisoSiExpiro() {
  if (new URLSearchParams(window.location.search).get('sesion') === 'expirada') {
    const msgEl = document.getElementById('msg-error');
    msgEl.style.color = '#ffb693'; // color primario, no de error (es aviso, no fallo)
    msgEl.innerText = 'Sesión cerrada. Debes iniciar sesión de nuevo.';
  }
})();
