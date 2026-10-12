
(function verificarSesionActiva() {
    if (!sessionStorage.getItem('miTokenJWT')) {
        window.location.replace('/iniciar_sesion?sesion=expirada');
    }
})();

function cerrarSesion() {
    sessionStorage.removeItem('miTokenJWT');
    window.location.replace('/iniciar_sesion?sesion=expirada');
}

document.getElementById("formulario-materias").addEventListener("submit", async function(e){
    e.preventDefault();
    const btn = document.getElementById("btn-crear");
    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined text-lg">progress_activity</span> Registrando...';

    const token = sessionStorage.getItem("miTokenJWT");

    // Hora: el input type="time" devuelve "HH:MM", exactamente lo que espera el backend
    const payload = {
        nombre:               document.getElementById("nombre").value.trim(),
        seccion:              document.getElementById("seccion").value.trim(),
        dia_semana:           parseInt(document.getElementById("dia_semana").value),
        hora_inicio:          document.getElementById("hora_inicio").value,
        hora_fin:             document.getElementById("hora_fin").value,
        fecha_inicio_periodo: document.getElementById("fecha_inicio_periodo").value,
        semanas_duracion:     parseInt(document.getElementById("semanas_duracion").value)
    };

    const resp  = await fetch("/crear_materia", {
        method: "POST",
        headers: { "Content-Type": "application/json", "Authorization": "Bearer " + token },
        body: JSON.stringify(payload)
    });
    const datos = await resp.json();
    const el    = document.getElementById("creado");

    if (resp.ok) {
        el.innerText = "✓ " + datos.mensaje;
        el.style.color = "#ffb693";
        document.getElementById("formulario-materias").reset();
        btn.innerHTML = '<span class="material-symbols-outlined text-lg">check_circle</span> ¡Clase creada!';
        setTimeout(() => {
            btn.disabled = false;
            btn.innerHTML = '<span class="material-symbols-outlined text-lg">add_circle</span> Registrar Clase';
        }, 2000);
    } else {
        el.innerText = datos.detail;
        el.style.color = "#ffb4ab";
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-outlined text-lg">add_circle</span> Registrar Clase';
    }
});
