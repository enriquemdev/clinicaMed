
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('marcarTodo').addEventListener('click', function(e) {
        e.preventDefault();
        //seleccionarTodo();
        checkAll();
    });
    document.getElementById('desmarcarTodo').addEventListener('click', function(e) {
        e.preventDefault();
        //desmarcarTodo();
        uncheckAll();
    });
    document.getElementById('recepcionista').addEventListener('click', function(e) {
        e.preventDefault();
        recepcionista();
    });
    document.getElementById('doctor').addEventListener('click', function(e) {
        e.preventDefault();
        //desmarcarTodo();
        doctor();
    });
});

function seleccionarTodo() {
    for (let i=0; i < document.f1.elements.length; i++) {
        if(document.f1.elements[i].type === "checkbox") {
            document.f1.elements[i].checked = true;
        }
    }
}

function desmarcarTodo() {
    for (let i=0; i<document.f1.elements.length; i++) {
        if(document.f1.elements[i].type == "checkbox") {
            document.f1.elements[i].checked = false
        }
    }
}

function checkAll() {
    document.querySelectorAll('#checkboxesmark input[type=checkbox]').forEach(function(checkElement) {
        checkElement.checked = true;
    });
}

function uncheckAll() {
    document.querySelectorAll('#checkboxesmark input[type=checkbox]').forEach(function(checkElement) {
        checkElement.checked = false;
    });
    
}
function recepcionista() {
    document.querySelectorAll('.recep input[type=checkbox]').forEach(function(checkElement) {
        checkElement.checked = true;
    });
}
function doctor() {
    document.querySelectorAll('.doc input[type=checkbox]').forEach(function(checkElement) {
        checkElement.checked = true;
    });
}
function enfermera() {
    document.querySelectorAll('.enfermera input[type=checkbox]').forEach(function(checkElement) {
        checkElement.checked = true;
    });
}
