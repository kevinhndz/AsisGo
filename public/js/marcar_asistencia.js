
const parametros   = new URLSearchParams(window.location.search);
const idMateria    = parametros.get("id_materia");

const TOKEN_DEL_QR = parametros.get("token");

let ubicacion = { lat: null, lng: null };


let resolverUbicacion = null;
const promesaUbicacion = new Promise(resolve => { resolverUbicacion = resolve; });

function mostrarForm() {
    document.getElementById("formulario-container").style.display = "block";
    document.getElementById("pulse-anim").style.display = "none";
}

function setEstadoUbicacion(icono, texto, mostrarBotonReintentar) {
    document.getElementById("icono-ubicacion").textContent = icono;
    document.getElementById("estado-ubicacion").innerText = texto;
    document.getElementById("btn-pedir-ubicacion").classList.toggle("hidden", !mostrarBotonReintentar);
}

let watchId       = null;   // ID del watchPosition activo
let timeoutGPS    = null;   // ID del setTimeout de respaldo

function iniciarGPS() {
    // Limpiar cualquier intento anterior
    if (watchId !== null)    { navigator.geolocation.clearWatch(watchId); watchId = null; }
    if (timeoutGPS !== null) { clearTimeout(timeoutGPS); timeoutGPS = null; }

    if (!navigator.geolocation) {
        setEstadoUbicacion("location_off", "Tu navegador no soporta geolocalización — se registrará sin validar rango.", false);
        resolverUbicacion({ lat: null, lng: null });
        mostrarForm();
        return;
    }

    setEstadoUbicacion("my_location", "Solicitando permiso de ubicación...", false);

  
    watchId = navigator.geolocation.watchPosition(
        function onExito(pos) {
            // Limpiamos el watch apenas tenemos la primera posición válida
            if (watchId !== null) { navigator.geolocation.clearWatch(watchId); watchId = null; }
            if (timeoutGPS !== null) { clearTimeout(timeoutGPS); timeoutGPS = null; }

            const coords = { lat: pos.coords.latitude, lng: pos.coords.longitude };
            ubicacion = coords;
            setEstadoUbicacion("location_on", `Ubicación obtenida ✓ (±${Math.round(pos.coords.accuracy)}m)`, false);
            resolverUbicacion(coords);
            mostrarForm();
        },
        function onError(err) {
            if (watchId !== null) { navigator.geolocation.clearWatch(watchId); watchId = null; }
            if (timeoutGPS !== null) { clearTimeout(timeoutGPS); timeoutGPS = null; }

            const msgs = {
                1: "Permiso denegado — se registrará sin validar rango GPS.",
                2: "Ubicación no disponible — se registrará sin validar rango GPS.",
                3: "Tiempo agotado — se registrará sin validar rango GPS."
            };
            // Solo mostramos "Reintentar" cuando el permiso fue denegado (código 1),
            // porque en los otros casos reintentar no sirve de mucho.
            setEstadoUbicacion("location_off", msgs[err.code] || "Sin ubicación.", err.code === 1);
            resolverUbicacion({ lat: null, lng: null });
            mostrarForm();
        },
        
        { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
    );

  
    timeoutGPS = setTimeout(() => {
        if (watchId !== null) { navigator.geolocation.clearWatch(watchId); watchId = null; }
        setEstadoUbicacion(
            "location_off",
            "No se recibió respuesta del GPS. Puedes marcar igual o reintentar.",
            true
        );
        resolverUbicacion({ lat: null, lng: null });
        mostrarForm();
    }, 17000); // 2 s más que el timeout interno de watchPosition
}

function pedirUbicacionManual() {
    // Al reintentar manualmente solo actualizamos la UI y volvemos a pedir GPS.
    // La promesaUbicacion ya se resolvió; ahora actualizamos `ubicacion` directo.
    ubicacion = { lat: null, lng: null };
    if (watchId !== null)    { navigator.geolocation.clearWatch(watchId); watchId = null; }
    if (timeoutGPS !== null) { clearTimeout(timeoutGPS); timeoutGPS = null; }

    if (!navigator.geolocation) return;

    setEstadoUbicacion("my_location", "Reintentando obtener ubicación...", false);
    document.getElementById("btn-pedir-ubicacion").classList.add("hidden");

    watchId = navigator.geolocation.watchPosition(
        function(pos) {
            if (watchId !== null) { navigator.geolocation.clearWatch(watchId); watchId = null; }
            ubicacion = { lat: pos.coords.latitude, lng: pos.coords.longitude };
            setEstadoUbicacion("location_on", `Ubicación obtenida ✓ (±${Math.round(pos.coords.accuracy)}m)`, false);
        },
        function(err) {
            if (watchId !== null) { navigator.geolocation.clearWatch(watchId); watchId = null; }
            setEstadoUbicacion("location_off", "No se pudo obtener la ubicación.", true);
        },
        { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
    );
}

// Arrancar GPS al cargar la página
iniciarGPS();

// ─── ENVIAR ASISTENCIA ───────────────────────────────────────────────────────
document.getElementById("formulario-asistencia").addEventListener("submit", async function(e) {
    e.preventDefault();
    const btn   = document.getElementById("btn-marcar");
    const msgEl = document.getElementById("mensaje");

    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined text-lg">progress_activity</span> Registrando...';
    msgEl.innerText = "";

    const numero_cuenta = document.getElementById("numero_cuenta").value.trim();

    const tokenAEnviar = TOKEN_DEL_QR;

    try {
        const respuesta = await fetch(`/marcar_asistencia?id_materia=${idMateria}`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                token:         tokenAEnviar,
                numero_cuenta: numero_cuenta,
                lat:           ubicacion.lat,
                lng:           ubicacion.lng
            })
        });
        const datos = await respuesta.json();

        if (respuesta.ok) {
            document.getElementById("formulario-container").style.display  = "none";
            document.getElementById("estado-ubicacion-card").style.display = "none";
            document.getElementById("estado-exito").style.display          = "block";
            document.getElementById("mensaje-exito").innerText             = datos.mensaje;
        } else {
            msgEl.innerText    = datos.detail;
            msgEl.style.color  = "#ffb4ab";
            btn.disabled       = false;
            btn.innerHTML      = '<span class="material-symbols-outlined text-lg">how_to_reg</span> Marcar Asistencia';
        }
    } catch (_) {
        msgEl.innerText   = "Error de red. Verifica tu conexión e intenta de nuevo.";
        msgEl.style.color = "#ffb4ab";
        btn.disabled      = false;
        btn.innerHTML     = '<span class="material-symbols-outlined text-lg">how_to_reg</span> Marcar Asistencia';
    }
});
