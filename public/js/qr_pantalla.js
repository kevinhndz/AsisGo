
    // El id_materia y base_url vienen inyectados por Jinja2
    const ID_MATERIA = document.body.dataset.idMateria;
    const BASE_URL = window.location.origin;

    let intervalo      = null;
    let intervaloBarra = null;

    async function renovarQR() {
        // Flash de desvanecimiento
        const wrapper = document.getElementById("qr-wrapper");
        wrapper.classList.add("renovando");

        const resp = await fetch(`/materia/${ID_MATERIA}/generar_qr_token_publico`);
        if (!resp.ok) { wrapper.classList.remove("renovando"); return; }

        const datos = await resp.json();
        const urlQR = `${datos.base_url}/marcar?id_materia=${ID_MATERIA}&token=${datos.token}`;

        const cont = document.getElementById("contenedor-qr");
        cont.innerHTML = "";

        setTimeout(() => {
            new QRCode(cont, { text: urlQR, width: 280, height: 280 });
            wrapper.classList.remove("renovando");
        }, 200);

        // Reiniciar barra de progreso
        iniciarBarra();
    }

    function iniciarBarra() {
        const barra = document.getElementById("barra");
        const texto = document.getElementById("contador-texto");

        if (intervaloBarra) clearInterval(intervaloBarra);

        let seg = 15;
        barra.style.transform = "scaleX(1)";
        texto.innerText = `Se renueva en ${seg}s`;

        intervaloBarra = setInterval(() => {
            seg--;
            const pct = seg / 15;
            barra.style.transform = `scaleX(${pct})`;
            texto.innerText = `Se renueva en ${seg}s`;
            if (seg <= 0) clearInterval(intervaloBarra);
        }, 1000);
    }

    // Arrancar
    renovarQR();
    intervalo = setInterval(renovarQR, 15000);
    