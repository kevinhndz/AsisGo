
// ══════════════════════════════════════════════════════════════
// BUG #1 — Guard de sesión: se ejecuta INMEDIATAMENTE al cargar
// la página. Si no hay token (usuario cerró sesión), redirige al
// login con ?sesion=expirada. Cubre el caso del botón "atrás".
// ══════════════════════════════════════════════════════════════
(function verificarSesionActiva() {
    if (!sessionStorage.getItem('miTokenJWT')) {
        window.location.replace('/iniciar_sesion?sesion=expirada');
    }
})();

// BUG #1 — Logout limpio: elimina el token y usa replace() para
// que la página del workspace NO quede en el historial del navegador.
function cerrarSesion() {
    sessionStorage.removeItem('miTokenJWT');
    window.location.replace('/iniciar_sesion?sesion=expirada');
}

// ── Estado ──────────────────────────────────────────────────
let idMateriaActual = null;
let intervaloQR     = null;
let intervaloVivo   = null;

function tkn()  { return sessionStorage.getItem("miTokenJWT"); }
function hdrs() { return { "Content-Type":"application/json","Authorization":"Bearer "+tkn() }; }

// ── LISTA DE CLASES (cards con imagen, como el diseño original) ──────────────
const IMAGENES_CLASES = [
    "https://lh3.googleusercontent.com/aida-public/AB6AXuCOW_4treGUJSyezOR81pVks2OGaLWAzLHcZlu2v2rOJrfFLqHQtlG-PH2Z3dN_v-nYcJhKh_XaEKCdt_rDAilLmezNWgIHIPnVbO78nkIteuWI-2rn4BpDOaH_Zer7zaAF1bW9Iw2XcdD2vtescNv3Avp4-667tvgJJGL0mXCPhQCJskvTlTtAetIChMG--ffbA2Enqyv5JdnT321LnBcfaeeNaI7doE1WL9ksHFgXULFuPZXlKku8uw",
    "https://lh3.googleusercontent.com/aida-public/AB6AXuD-XjExAdDWgrPWJPHgIzLIBD5VBx91sLxUyhWRMk_8WJ_uWwKr31gawSfyAyYqKH0YTH5wG-8L-kK5qjuVrAC8MjJJIocZQFpkagj6S4HzfqkOAvBdwQcUFUJlNUdSsy4bFfeowkxnhgZYi-1A2b5l8-q-aHir3RnoBw6YQDI4Qft6dy_oht7OI7xq61bEriFb2jw5aC743x0kptQqzLxgdHHqYf_IlK-rZf5hkaIPGNSP_GDPIWOXbw",
    "https://lh3.googleusercontent.com/aida-public/AB6AXuAxXT3x7V-orejNkHdJiWOXkioTGmhSTERz7vQlb3YQ38J-gBoXpD0IOuLbjvqW82cjsmLm64LQOMAmlRMUaEvgQ-AA7_WUU_KOr9Ik8Uw2kG0qKJbaXmUCEoGJLEfKbnCKNyiveh2N60SpMSmzNe0nrv9kGIV1bCEEmWkKWE-CyescOTB0wknEG7A5s-1913vqQtxmOjtiLIq4pXgmtMov2iX3WZfs3FtLoNSGNdDw6d3A5kQeHlq9cw",
    "https://lh3.googleusercontent.com/aida-public/AB6AXuDQdpFObxe9Sqnl_jKfGu9vg34um5DN1M6HDITbQVr26C79II9Rm3aDkSCRzWaXIHIKCGGoIVqQ2GgM0wFDcfN1pTzrKotNbHS51RmGwCMDymnsKHw4JKxorfMmN4PzNeo7GFEW6Xk2i4RL23PXx3VGGRyGtu3hf3aUSdlBs6PyDEk_jwuJnPs7X1ZFZo39lL_vxckVllA3sGsYY-6q57G0JUwXmIEEBkzdgj4fjshw2LqdqhEDMdj1jg"
];

const NOMBRES_DIAS_MAP = {
    0: "Lunes",
    1: "Martes",
    2: "Miércoles",
    3: "Jueves",
    4: "Viernes",
    5: "Sábado",
    6: "Domingo"
};

async function cargarClases() {
    const cont = document.getElementById("contenedor-materias");
    let resp, materias;
    try {
        resp    = await fetch("/mis_materias", { headers:{"Authorization":"Bearer "+tkn()} });
        materias = await resp.json();
    } catch (err) {
        cont.innerHTML = `<div class="glass-card p-8 text-center">
            <span class="material-symbols-outlined text-error text-5xl mb-3 block">wifi_off</span>
            <p class="text-error text-sm font-bold">Error de conexión. Recarga la página.</p>
        </div>`;
        return;
    }

    if (!resp.ok || materias.length === 0) {
        cont.innerHTML = `<div class="glass-card p-8 text-center">
            <span class="material-symbols-outlined text-outline text-5xl mb-3 block">school</span>
            <p class="text-on-surface-variant text-sm">No tienes clases registradas aún.</p>
            <a href="/interface" class="inline-flex items-center gap-1.5 text-primary font-bold text-sm mt-3 hover:underline underline-offset-4">
                <span class="material-symbols-outlined text-base">add_circle</span> Crear primera clase
            </a>
        </div>`;
        return;
    }

    let html = `<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-gutter">`;

    materias.forEach((m, idx) => {
        const imagenUrl = IMAGENES_CLASES[idx % IMAGENES_CLASES.length];
        const diaNombre = m.dia_semana !== null ? NOMBRES_DIAS_MAP[m.dia_semana] : "Sin día";
        const esActiva = idx === 0; // La primera clase está "ACTIVA"
        const nombreEscapado = m.nombre.replace(/'/g, "\\'");
        const seccionEscapada = m.seccion.replace(/'/g, "\\'");

        const badgeHtml = esActiva
            ? `<div class="absolute top-4 right-4 z-20 bg-primary/20 backdrop-blur-md px-3 py-1 rounded-full border border-primary/30">
                 <span class="font-mono-caps text-primary tracking-widest uppercase text-[10px]">Activa</span>
               </div>`
            : `<div class="absolute top-4 right-4 z-20 bg-white/5 backdrop-blur-md px-3 py-1 rounded-full border border-white/10">
                 <span class="font-mono-caps text-on-surface-variant tracking-widest uppercase text-[10px]">Próxima</span>
               </div>`;

        html += `
        <div class="group relative flex flex-col bg-surface-container rounded-xl overflow-hidden row-in transition-all duration-500 hover:-translate-y-2 hover:shadow-[0_20px_40px_rgba(0,0,0,0.4)]">
            <div class="h-48 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-t from-surface-container via-transparent to-transparent z-10"></div>
                <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" src="${imagenUrl}" alt="${m.nombre}">
                ${badgeHtml}
                <button onclick="confirmarEliminarClase(${m.id_materia}, '${nombreEscapado}')"
                    title="Eliminar clase"
                    class="absolute top-4 left-4 z-20 w-9 h-9 rounded-full bg-error/20 backdrop-blur-md text-error border border-error/30 hover:bg-error/30 transition-all active:scale-90 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[18px]">delete</span>
                </button>
            </div>
            <div class="p-6 flex flex-col flex-1">
                <div class="mb-6">
                    <h2 class="font-title text-title-md text-on-surface mb-1">${m.nombre}</h2>
                    <div class="flex items-center gap-1 text-on-surface-variant">
                        <span class="material-symbols-outlined workspace-tag-icon">tag</span>
                        <span class="font-mono-caps text-[11px] tracking-widest">SEC-${m.seccion}</span>
                    </div>
                </div>
                <div class="space-y-3 mb-6">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-[20px]">calendar_today</span>
                        <p class="text-sm text-on-surface-variant">${diaNombre}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-[20px]">schedule</span>
                        <p class="text-sm text-on-surface-variant">${m.hora_inicio} - ${m.hora_fin}</p>
                    </div>
                </div>
                <button onclick="irAClase(${m.id_materia}, '${nombreEscapado}', '${seccionEscapada}')"
                    class="mt-auto w-full py-4 bg-primary text-on-primary font-title text-xs font-bold uppercase tracking-widest rounded-xl transition-all hover:opacity-90 active:scale-95 flex items-center justify-center gap-2">
                    Entrar a clase
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </button>
            </div>
        </div>`;
    });

    html += `</div>`;
    cont.innerHTML = html;
}

function irAClase(idMateria, nombre, seccion) {
    idMateriaActual = idMateria;
    document.getElementById("vista-lista").style.display   = "none";
    document.getElementById("vista-detalle").style.display = "block";
    document.getElementById("titulo-clase").innerText = nombre;

    const base = window.location.origin;
    document.getElementById("link-inscripcion").innerHTML =
        `🔗 Link inscripción alumnos: <a href="${base}/inscribirse/${seccion}" target="_blank" class="text-primary hover:underline underline-offset-2">${base}/inscribirse/${seccion}</a>`;

    // Reset del bloque QR inline (placeholder) al entrar a la clase
    document.getElementById("qr-inline-wrapper").style.display = "none";
    document.getElementById("qr-placeholder").style.display = "flex";

    cargarEstudiantes();
    verificarClaseEnCurso();
}

function volverALista() {
    cerrarModalQR();
    if (intervaloVivo) { clearInterval(intervaloVivo); intervaloVivo = null; }
    document.getElementById("vista-detalle").style.display = "none";
    document.getElementById("vista-lista").style.display   = "block";
    idMateriaActual = null;
}

// ── CLASE EN CURSO ───────────────────────────────────────────
async function verificarClaseEnCurso() {
    const resp  = await fetch(`/materia/${idMateriaActual}/clase_en_curso`, { headers:{"Authorization":"Bearer "+tkn()} });
    const datos = await resp.json();
    const sec   = document.getElementById("seccion-vivo");
    if (datos.en_curso) {
        sec.style.display = "flex";
        cargarAsistenciaVivo();
        if (!intervaloVivo) intervaloVivo = setInterval(cargarAsistenciaVivo, 10000);
    } else {
        sec.style.display = "none";
        if (intervaloVivo) { clearInterval(intervaloVivo); intervaloVivo = null; }
    }
}

async function cargarAsistenciaVivo() {
    // Solo actualiza el panel en vivo. La tabla de estudiantes NO se recarga
    // automáticamente para evitar el parpadeo cada 10s.
    const resp  = await fetch(`/materia/${idMateriaActual}/asistencia_hoy`, { headers:{"Authorization":"Bearer "+tkn()} });
    if (!resp.ok) return;
    const datos = await resp.json();
    const cont  = document.getElementById("contenedor-vivo");
    const presentes = datos.presentes;
    const ausentes  = datos.ausentes;

    let html = `
    <div class="grid grid-cols-2 gap-4 mb-5">
        <div class="bg-white/5 rounded-xl p-5 border border-white/5 flex flex-col justify-center">
            <span class="font-mono-caps text-[10px] text-on-surface-variant mb-1 tracking-widest">PRESENTES</span>
            <div class="flex items-baseline gap-2">
                <span class="font-display text-4xl sm:text-5xl font-bold text-primary">${presentes.length}</span>
                <span class="font-title text-sm text-on-surface-variant">/ ${presentes.length + ausentes.length}</span>
            </div>
        </div>
        <div class="bg-white/5 rounded-xl p-5 border border-white/5 flex flex-col justify-center">
            <span class="font-mono-caps text-[10px] text-on-surface-variant mb-1 tracking-widest">AUSENTES</span>
            <div class="flex items-baseline gap-2">
                <span class="font-display text-4xl sm:text-5xl font-bold text-on-surface">${ausentes.length}</span>
            </div>
        </div>
    </div>
    <div class="overflow-x-auto rounded-xl border border-white/5 bg-surface/20">
    <table class="w-full"><thead class="t-vivo"><tr><th>Estudiante</th><th>Modalidad</th><th class="text-right">Estado</th></tr></thead><tbody>`;

    presentes.forEach(e => {
        html += `<tr class="row-in"><td class="flex items-center gap-2 text-on-surface">
                <span class="w-6 h-6 rounded-full bg-white/5 flex items-center justify-center font-bold text-[10px] text-primary">${(e.nombre||'').split(' ').map(p=>p[0]).slice(0,2).join('').toUpperCase()}</span>
                ${e.nombre}</td>
            <td><span class="badge-modalidad capitalize">${e.modalidad}</span></td>
            <td class="text-right"><span class="pill bg-[#2e4033]/60 border border-success/20 text-success"><span class="w-1.5 h-1.5 rounded-full bg-success"></span>Presente</span></td></tr>`;
    });
    ausentes.forEach(e => {
        html += `<tr class="row-in"><td class="flex items-center gap-2 text-on-surface">
                <span class="w-6 h-6 rounded-full bg-white/5 flex items-center justify-center font-bold text-[10px] text-on-surface-variant">${(e.nombre||'').split(' ').map(p=>p[0]).slice(0,2).join('').toUpperCase()}</span>
                ${e.nombre}</td>
            <td><span class="badge-modalidad capitalize">${e.modalidad}</span></td>
            <td class="text-right"><span class="pill bg-white/10 border border-white/10 text-on-surface-variant"><span class="w-1.5 h-1.5 rounded-full bg-on-surface-variant"></span>Sin marcar</span></td></tr>`;
    });

    html += "</tbody></table></div>";
    cont.innerHTML = html;
}

// ── TABLA ESTUDIANTES (fechas reales del periodo, faltas editables, notas, PDF) ──
async function cargarEstudiantes() {
    const cont = document.getElementById("contenedor-tabla");
    cont.innerHTML = `<p class="text-sm text-on-surface-variant p-4">Cargando...</p>`;

    const resp = await fetch(`/materia/${idMateriaActual}/reporte_asistencia`, { headers:{"Authorization":"Bearer "+tkn()} });
    const datos = await resp.json();

    if (!resp.ok) { cont.innerHTML = `<p class="text-sm text-error p-4">${datos.detail}</p>`; return; }
    if (datos.estudiantes.length === 0) {
        cont.innerHTML = `<div class="text-center py-8 text-on-surface-variant text-sm">
            <span class="material-symbols-outlined text-4xl block mb-2 text-outline">group</span>
            Sin estudiantes inscritos aún.
        </div>`;
        return;
    }

    const fechas = datos.fechas_columnas;
    const fechasOrdenDesc = [...fechas].reverse();

    let html = `<table class="w-full min-w-[700px]"><thead class="t-head"><tr>
        <th>Nombre</th><th>Correo</th><th>Cuenta</th><th class="text-center">Modalidad</th><th class="text-right">Faltas</th>`;
    fechasOrdenDesc.forEach(f => {
        const [anio, mes, dia] = f.split("-");
        html += `<th class="text-center">${dia}/${mes}</th>`;
    });
    html += `<th>Notas</th><th class="text-center">Acciones</th></tr></thead><tbody class="t-body">`;

    datos.estudiantes.forEach(e => {
        const mapaFechas = {};
        e.fechas.forEach(f => { mapaFechas[f.fecha] = f.presente; });

        html += `<tr class="group/row row-in">
            <td class="font-medium whitespace-nowrap">${e.nombre}</td>
            <td class="text-on-surface-variant">${e.correo}</td>
            <td class="font-mono-caps text-[11px] text-on-surface-variant">${e.numero_cuenta}</td>
            <td class="text-center"><span class="badge-modalidad capitalize">${e.modalidad}</span></td>
            <td class="text-right font-bold ${e.faltas > 0 ? 'text-error' : 'text-primary'}">${e.faltas}</td>`;

        fechasOrdenDesc.forEach(f => {
            const presente = mapaFechas[f];
            const color = presente ? "text-primary" : "text-error";
            const icono = presente ? "check_circle" : "cancel";
            html += `<td class="text-center">
                <button onclick="toggleAsistencia(${e.id_estudiante}, '${f}', ${!presente})"
                    class="material-symbols-outlined text-lg ${color} hover:opacity-70 transition-opacity cursor-pointer"
                    title="Click para corregir">${icono}</button>
            </td>`;
        });

        html += `<td>
            <input type="text" value="${(e.notas || '').replace(/"/g, '&quot;')}"
                placeholder="Nota del profesor..."
                onblur="guardarNota(${e.id_estudiante}, this.value)"
                class="w-40 bg-surface-container-low rounded-lg py-1.5 px-2 text-xs text-on-surface outline-none focus:ring-1 focus:ring-primary/40"/>
        </td>`;

        html += `<td>
            <div class="flex justify-center gap-1 opacity-0 group-hover/row:opacity-100 transition-opacity">
                <button onclick='abrirModalEditar(${JSON.stringify(e)})'
                    title="Editar datos"
                    class="p-1.5 text-on-surface-variant hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[16px]">edit</span>
                </button>
                <button
                    data-id="${e.id_estudiante}"
                    data-nombre="${e.nombre.replace(/"/g, '&quot;')}"
                    data-faltas="${e.faltas}"
                    onclick="abrirModalFaltasDesdeBtn(this)"
                    title="Ajustar faltas"
                    class="p-1.5 text-amber-400 hover:text-amber-300 transition-colors">
                    <span class="material-symbols-outlined text-[16px]">event_busy</span>
                </button>
                <button onclick="confirmarEliminar(${e.id_estudiante})"
                    title="Eliminar estudiante"
                    class="p-1.5 text-on-surface-variant hover:text-error transition-colors">
                    <span class="material-symbols-outlined text-[16px]">delete</span>
                </button>
            </div>
        </td></tr>`;
    });

    html += "</tbody></table>";
    cont.innerHTML = html;

    // Botón de descarga PDF (fuera de la tabla, dentro del contenedor de la card)
    const yaExiste = document.getElementById("btn-descargar-pdf-wrapper");
    if (!yaExiste) {
        const wrap = document.createElement("div");
        wrap.id = "btn-descargar-pdf-wrapper";
        wrap.className = "flex justify-end mt-4";
        wrap.innerHTML = `<button onclick="descargarPDF()"
                class="flex items-center gap-2 bg-white/5 border border-white/10 rounded-lg text-on-surface hover:bg-white/10 transition-colors text-sm font-medium px-5 py-2 active:scale-[0.98]">
                <span class="material-symbols-outlined text-[18px]">download</span> Descargar PDF
            </button>`;
        cont.parentElement.appendChild(wrap);
    }
}

// FIX: toggle de asistencia — siempre recarga la tabla (éxito o fallo) para
// que el ícono nunca quede desincronizado con la BD.
async function toggleAsistencia(idEstudiante, fecha, nuevoPresente) {
    const resp = await fetch(`/materia/${idMateriaActual}/corregir_asistencia`, {
        method: "PUT",
        headers: hdrs(),
        body: JSON.stringify({ id_estudiante: idEstudiante, fecha: fecha, presente: nuevoPresente })
    });
    // Recargamos siempre: si hubo error el ícono vuelve al valor correcto de la BD
    cargarEstudiantes();
}

// NUEVO: guardar nota al salir del campo de texto (blur)
async function guardarNota(idEstudiante, texto) {
    await fetch(`/estudiante/${idEstudiante}/notas`, {
        method: "PUT",
        headers: hdrs(),
        body: JSON.stringify({ notas: texto })
    });
}

// NUEVO: descargar el PDF del reporte
function descargarPDF() {
    fetch(`/materia/${idMateriaActual}/reporte_pdf`, { headers:{"Authorization":"Bearer "+tkn()} })
        .then(resp => resp.blob())
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = url;
            a.download = "asistencia.pdf";
            document.body.appendChild(a);
            a.click();
            a.remove();
        });
}

// ── ELIMINAR CLASE ───────────────────────────────────────────
async function confirmarEliminarClase(idMateria, nombreClase) {
    if (!confirm(`¿Eliminar la clase "${nombreClase}"?\n\nEsto borrará también todos los estudiantes y registros de asistencia. Esta acción no se puede deshacer.`)) return;
    const resp = await fetch(`/materia/${idMateria}`, { method: "DELETE", headers: { "Authorization": "Bearer " + tkn() } });
    if (resp.ok) {
        cargarClases();
    } else {
        const d = await resp.json();
        alert(d.detail || "Error al eliminar la clase.");
    }
}

// ── AJUSTAR FALTAS ───────────────────────────────────────────

// FIX BUG 2A: nueva función que lee los datos desde atributos data-* del botón,
// evitando el problema de escape de comillas/apóstrofes en nombres de estudiantes
// cuando se construyen con template strings en innerHTML (ej. "O'Brien" rompía el JS).
function abrirModalFaltasDesdeBtn(btn) {
    const idEstudiante   = btn.dataset.id;
    const nombre         = btn.dataset.nombre;
    const faltasActuales = parseInt(btn.dataset.faltas) || 0;
    abrirModalFaltas(idEstudiante, nombre, faltasActuales);
}

function abrirModalFaltas(idEstudiante, nombre, faltasActuales) {
    document.getElementById("faltas-id-estudiante").value  = idEstudiante;
    document.getElementById("modal-faltas-nombre").innerText = `Estudiante: ${nombre}`;
    // FIX BUG 2B: aseguramos que el input siempre tenga el valor actual de faltas
    // y no quede vacío o con el valor de la última vez que se abrió el modal.
    const inputFaltas = document.getElementById("faltas-cantidad");
    inputFaltas.value = faltasActuales;
    inputFaltas.min   = 0;
    document.getElementById("modal-faltas-msg").innerText  = "";
    document.getElementById("modal-faltas-msg").style.color = "";
    document.getElementById("modal-faltas").classList.add("activo");
    // Enfocar el input para que el profesor pueda escribir de inmediato
    setTimeout(() => inputFaltas.focus(), 100);
}

async function guardarFaltas() {
    const idEst   = document.getElementById("faltas-id-estudiante").value;
    const faltas  = parseInt(document.getElementById("faltas-cantidad").value);
    const msgEl   = document.getElementById("modal-faltas-msg");
    const btnGuardar = document.getElementById("btn-guardar-faltas");

    if (isNaN(faltas) || faltas < 0) {
        msgEl.innerText    = "Ingresa un número válido (0 o más).";
        msgEl.style.color  = "#ffb4ab";
        return;
    }

    // FIX BUG 2C: deshabilitar el botón mientras espera la respuesta para
    // evitar doble envío si el profesor hace doble clic.
    if (btnGuardar) btnGuardar.disabled = true;
    msgEl.innerText   = "Guardando...";
    msgEl.style.color = "#d7c2ba";

    const resp  = await fetch(`/estudiante/${idEst}/ajustar_faltas`, {
        method: "PUT",
        headers: hdrs(),
        body: JSON.stringify({ faltas })
    });
    const datos = await resp.json();

    if (resp.ok) {
        msgEl.innerText   = "✓ " + datos.mensaje;
        msgEl.style.color = "#ffb693";
        // Cerrar el modal tras un breve momento para que el profesor vea la confirmación
        setTimeout(() => {
            document.getElementById("modal-faltas").classList.remove("activo");
            cargarEstudiantes();
        }, 800);
    } else {
        msgEl.innerText   = datos.detail || "Error al guardar.";
        msgEl.style.color = "#ffb4ab";
        if (btnGuardar) btnGuardar.disabled = false;
    }
}

document.getElementById("modal-faltas").addEventListener("click", e => { if (e.target === e.currentTarget) document.getElementById("modal-faltas").classList.remove("activo"); });

// ── CRUD ESTUDIANTES ─────────────────────────────────────────
function abrirModalAgregar() {
    document.getElementById("modal-form-titulo").innerText = "Agregar Estudiante";
    ["form-id-estudiante","form-nombre","form-correo","form-telefono","form-cuenta"].forEach(id => document.getElementById(id).value = "");
    document.getElementById("form-modalidad").value = "presencial";
    document.getElementById("modal-form-mensaje").innerText = "";
    document.getElementById("modal-form").classList.add("activo");
}
function abrirModalEditar(est) {
    document.getElementById("modal-form-titulo").innerText = "Editar Estudiante";
    document.getElementById("form-id-estudiante").value = est.id_estudiante;
    document.getElementById("form-nombre").value    = est.nombre;
    document.getElementById("form-correo").value    = est.correo;
    document.getElementById("form-telefono").value  = est.telefono || "";
    document.getElementById("form-cuenta").value    = est.numero_cuenta;
    document.getElementById("form-modalidad").value = est.modalidad;
    document.getElementById("modal-form-mensaje").innerText = "";
    document.getElementById("modal-form").classList.add("activo");
}
function cerrarModalForm() { document.getElementById("modal-form").classList.remove("activo"); }

async function guardarEstudiante() {
    const idEst = document.getElementById("form-id-estudiante").value;
    const body  = {
        nombre:        document.getElementById("form-nombre").value.trim(),
        correo:        document.getElementById("form-correo").value.trim(),
        telefono:      document.getElementById("form-telefono").value.trim(),
        numero_cuenta: document.getElementById("form-cuenta").value.trim(),
        modalidad:     document.getElementById("form-modalidad").value
    };
    const msgEl  = document.getElementById("modal-form-mensaje");
    const url    = idEst ? `/estudiante/${idEst}` : `/materia/${idMateriaActual}/agregar_estudiante`;
    const method = idEst ? "PUT" : "POST";
    const resp   = await fetch(url, { method, headers:hdrs(), body:JSON.stringify(body) });
    const datos  = await resp.json();
    if (resp.ok) { cerrarModalForm(); cargarEstudiantes(); }
    else { msgEl.innerText = datos.detail; msgEl.style.color = "#ffb4ab"; }
}

async function confirmarEliminar(idEst) {
    if (!confirm("¿Eliminar este estudiante de la clase?")) return;
    const resp = await fetch(`/estudiante/${idEst}`, { method:"DELETE", headers:{"Authorization":"Bearer "+tkn()} });
    if (resp.ok) cargarEstudiantes();
    else { const d = await resp.json(); alert(d.detail); }
}

// ── CONFIGURAR UBICACIÓN AULA ────────────────────────────────
function configurarUbicacionAula() {
    const el = document.getElementById("estado-ubicacion-aula");
    if (!navigator.geolocation) { el.innerText = "Tu navegador no soporta geolocalización."; el.style.color="#ffb4ab"; return; }
    el.innerText = "Obteniendo tu ubicación..."; el.style.color = "#d7c2ba";
    navigator.geolocation.getCurrentPosition(async function(pos) {
        const resp  = await fetch(`/materia/${idMateriaActual}/configurar_ubicacion`, {
            method:"POST", headers:hdrs(), body:JSON.stringify({lat:pos.coords.latitude, lng:pos.coords.longitude})
        });
        const datos = await resp.json();
        if (resp.ok) { el.innerText = "✓ Ubicación del aula guardada."; el.style.color="#ffb693"; }
        else { el.innerText = datos.detail; el.style.color="#ffb4ab"; }
    }, function() { el.innerText = "No se pudo obtener ubicación."; el.style.color="#ffb4ab"; });
}

// ── QR ───────────────────────────────────────────────────────
async function pedirTokenYPintarQR() {
    const resp = await fetch(`/materia/${idMateriaActual}/generar_qr_token`, { headers:{"Authorization":"Bearer "+tkn()} });
    if (!resp.ok) { document.getElementById("contenedor-qr").innerHTML = `<p class="text-error text-xs p-4">Error generando el código.</p>`; return; }
    const datos = await resp.json();
    const urlQR = `${datos.base_url}/marcar?id_materia=${idMateriaActual}&token=${datos.token}`;

    // Modal QR (grande)
    const cont = document.getElementById("contenedor-qr");
    cont.innerHTML = "";
    new QRCode(cont, { text:urlQR, width:220, height:220 });

    // QR inline en la card (mismo token, versión pequeña de referencia visual)
    const contInline = document.getElementById("contenedor-qr-inline");
    contInline.innerHTML = "";
    new QRCode(contInline, { text:urlQR, width:150, height:150 });
    document.getElementById("qr-placeholder").style.display = "none";
    document.getElementById("qr-inline-wrapper").style.display = "inline-block";

    let seg = 15;
    const countEl = document.getElementById("contador-qr");
    countEl.innerText = `Se renueva en ${seg}s`;
    const cuenta = setInterval(() => { seg--; countEl.innerText=`Se renueva en ${seg}s`; if(seg<=0) clearInterval(cuenta); }, 1000);
    verificarClaseEnCurso();
}

function abrirModalQR() {
    document.getElementById("modal-qr").classList.add("activo");
    pedirTokenYPintarQR();
    intervaloQR = setInterval(pedirTokenYPintarQR, 15000);
}
function cerrarModalQR() {
    document.getElementById("modal-qr").classList.remove("activo");
    if (intervaloQR) { clearInterval(intervaloQR); intervaloQR = null; }
}
function abrirVistGrande() { window.open(`/qr_pantalla/${idMateriaActual}`, "_blank"); }

document.getElementById("modal-qr").addEventListener("click",  e => { if(e.target===e.currentTarget) cerrarModalQR(); });
document.getElementById("modal-form").addEventListener("click", e => { if(e.target===e.currentTarget) cerrarModalForm(); });

// ── GRABACIÓN ────────────────────────────────────────────────
async function publicarGrabacion() {
    const url = document.getElementById("url-grabacion").value.trim();
    if (!url) { alert("Pega el URL de la grabación primero."); return; }
    const resp  = await fetch(`/materia/${idMateriaActual}/publicar_grabacion`, {
        method: "POST",
        headers: hdrs(),
        body: JSON.stringify({ url })
    });
    const datos = await resp.json();
    const el    = document.getElementById("estado-grabacion");
    el.innerText   = resp.ok ? "✓ " + datos.mensaje : datos.detail;
    el.style.color = resp.ok ? "#341000" : "#690005";
    if (resp.ok) document.getElementById("url-grabacion").value = "";
}

cargarClases();
