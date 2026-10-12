
const partesURL = window.location.pathname.split("/");
const seccion   = partesURL[partesURL.length - 1];
document.getElementById("mostrar-seccion").innerText = seccion;

document.getElementById("formulario-inscripcion").addEventListener("submit", async function(e) {
    e.preventDefault();
    const btn = document.getElementById("btn-inscribir");
    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined text-lg">progress_activity</span> Inscribiendo...';

    const nombre        = document.getElementById("nombre").value;
    const correo        = document.getElementById("correo").value;
    const telefono      = document.getElementById("telefono").value;
    const numero_cuenta = document.getElementById("numero_cuenta").value;
    const modalidad     = document.getElementById("modalidad").value;

    const respuesta = await fetch(`/inscribirse/${seccion}`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ nombre, correo, telefono, numero_cuenta, modalidad })
    });
    const datos = await respuesta.json();
    const msg   = document.getElementById("mensaje");

    if (respuesta.ok) {
        msg.innerText = "✓ " + datos.mensaje;
        msg.style.color = "#ffb693";
        document.getElementById("formulario-inscripcion").reset();
        btn.innerHTML = '<span class="material-symbols-outlined text-lg">check_circle</span> ¡Inscrito!';
    } else {
        msg.innerText = datos.detail;
        msg.style.color = "#ffb4ab";
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-outlined text-lg">how_to_reg</span> Inscribirme';
    }
});
