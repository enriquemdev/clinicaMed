
function rellenarData (nombre,apellido,cedula,genero,nacio,civil,direccion,telefono,email,) {
    /* var mensaje = document.createElement("h3");
    mensaje.textContent = "Por favor rellene los datos que faltan"
    document.getElementById('message').appendChild(mensaje); */
    //Lo siguiente es creación de input activar caso en controlador y no enviar datos de persona
    var activador = document.createElement("input");
    activador.hidden = true;
    activador.name="activador";
    activador.value="1";
    document.getElementById('message').appendChild(activador);


    document.getElementById('paciente_nombre').value = nombre;
    document.getElementById('paciente_apellido').value = apellido;
    document.getElementById('cedula_reg').value = cedula;
    document.getElementById('item_genero').value = genero;
    document.getElementById('paciente_nacio').value = nacio;
    document.getElementById('item_civil').value = civil;
    document.getElementById('paciente_direccion').value = direccion;
    document.getElementById('paciente_telefono').value = telefono;
    document.getElementById('paciente_correo').value = email;
    document.getElementById("paciente_inss").focus();  
}
function rellenar_Datos_Responsable(id,nombre){
var nombre_responsable = document.createElement("input");
var id_responsable = document.createElement("input");
nombre_responsable.readOnly = true;
nombre_responsable.value = nombre;
nombre_responsable.type = "text";
nombre_responsable.classList = "form-control"
id_responsable.readOnly = true;
id_responsable.value = id;
id_responsable.type = "text";
id_responsable.classList = "form-control"
id_responsable.name = "Responsable_ID_reg";
var idtext = document.createElement("label");
idtext.textContent= "ID DE RESPONSABLE"
var nombretext = document.createElement("label");
nombretext.textContent = "NOMBRE RESPONSABLE"
var salto = document.createElement("br")
document.getElementById('ResponsableInfo').appendChild(salto);
document.getElementById('ResponsableInfo').appendChild(salto);
document.getElementById('ResponsableInfo').appendChild(idtext);
document.getElementById('ResponsableInfo').appendChild(id_responsable);
document.getElementById('ResponsableInfo').appendChild(salto);
document.getElementById('ResponsableInfo').appendChild(nombretext);
document.getElementById('ResponsableInfo').appendChild(nombre_responsable);
}