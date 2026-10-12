
function togglePass() {
  const inp = document.getElementById('contrasena');
  const ico = document.getElementById('eye-icon');
  inp.type = inp.type === 'password' ? 'text' : 'password';
  ico.textContent = inp.type === 'password' ? 'visibility' : 'visibility_off';
}

async function handleSignup(e) {
  e.preventDefault();
  const btn    = document.getElementById('btn-submit');
  const txt    = document.getElementById('btn-text');
  const ico    = document.getElementById('btn-icon');
  const loader = document.getElementById('btn-loader');
  const msgEl  = document.getElementById('msg-error');

  btn.classList.add('pointer-events-none', 'opacity-80');
  ico.classList.add('hidden');
  loader.classList.remove('hidden');
  msgEl.innerText = '';

  const resp  = await fetch('/sign_up', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      nombre:    document.getElementById('nombre').value,
      correo:    document.getElementById('correo').value,
      telefono:  document.getElementById('telefono').value,
      usuario:   document.getElementById('usuario').value,
      contrasena: document.getElementById('contrasena').value
    })
  });
  const datos = await resp.json();

  loader.classList.add('hidden');
  ico.classList.remove('hidden');

  if (resp.ok) {
    sessionStorage.setItem('miTokenJWT', datos.token);
    document.getElementById('formulario-registro').classList.add('hidden');
    const s = document.getElementById('success-state');
    s.classList.remove('hidden');
    s.classList.add('flex');
    setTimeout(() => window.location.href = '/workspace', 1800);
  } else {
    msgEl.innerText = datos.detail;
    btn.classList.remove('pointer-events-none', 'opacity-80');
  }
}
