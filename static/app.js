

//vamos a HTML buscamos algun form que diga: "formulario-registro" - cuando le den click a al boton type submit
// add event listener escucha que le dieron click, si eso es True, crea una funcion 
document.getElementById("formulario-login").addEventListener("submit", async function(e) {
    e.preventDefault();  // la funcion es "e", asi que para que no cargue le HTML lo paramos para que de la repsuesta ahi mismo

    const usuario = document.getElementById("usuario").value; // cada dato en input type text trae un ID. SACAMOS ese valor con.value
    const contrasena = document.getElementById("contrasena").value;

    // Listo para mandar Crear, empaquetar el JSON y mandarlo por la web

    // await fetch - > le decimos a Js "Vamos a enviar un paquete por internet" mandalo a esta direccion
    // OJO: hay que asegurarse de que marque al endpoint / login donde tenemos la ruta para validar la contrasena
    //rellenamos los datos que SIEMPRE VAN. 
    const respuesta = await fetch("http://127.0.0.1:8000/login", {  
        method: "POST", // mandamos un post (esto siempre va)
        headers: { "Content-Type": "application/json" }, // le decimos a FastAPI -> "vas a revisar un hashmap" 
        body: JSON.stringify({ usuario: usuario, contrasena: contrasena })
        // esta es la parte mas importante, aqui esto siempre va, pero lo que hace es que un objeto JS no puede viajar por a red
        // asi que lo convertimos en text plano {}
    });

    const datos = await respuesta.json(); // creamos una nueva variable llamada datos para guardar la respuesta que mando Python
    

    //SI el statuu code es 200,
    if (respuesta.ok) {
        document.getElementById("mensaje").innerText = datos.mensaje;
        document.getElementById("mensaje").style.color = "green";
    } else {
        document.getElementById("mensaje").innerText = "Error: " + JSON.stringify(datos.detail);
        document.getElementById("mensaje").style.color = "red";
    }
});


document.getElementById("formulario-nuevo").addEventListener( "submit", async function (e){

 e.preventDefault();

 const nombre = document.getElementById("nombre").value;
 const telefono = document.getElementById("telefono").value;
 const usuario = document.getElementById("usuario").value;
 const contrasena = document.getElementById("contrasena").value;


 const repsuesta = await fetch("http://127.0.0.1:8000/sign_up",{

     method: "POST",
     headers: { "Content-Type": "application/json" },
     body: JSON.stringify({nombre: nombre, telefono: telefono, usuario: usuario, contrasena: contrasena })



 });

 const datos_recibidos = await respuesta.json();

 if (respuesta.ok){
    
    document.getElementById("bienvenida").innerText = datos_recibidos.usuario;
    document.getElementById("bienvenida").style.color = "green";

 }



});








