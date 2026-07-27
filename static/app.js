document.getElementById("formulario-registro").addEventListener("submit", async function(e) {
    e.preventDefault(); 

    const usuario = document.getElementById("usuario").value;
    const contrasena = document.getElementById("contrasena").value;

    const respuesta = await fetch("http://127.0.0.1:8000/login", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ usuario: usuario, contrasena: contrasena })
    });

    const datos = await respuesta.json();

    if (respuesta.ok) {
        document.getElementById("mensaje").innerText = datos.mensaje;
        document.getElementById("mensaje").style.color = "green";
    } else {
        document.getElementById("mensaje").innerText = "Error: " + JSON.stringify(datos.detail);
        document.getElementById("mensaje").style.color = "red";
    }
});