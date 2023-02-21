
function solicitarDatos(receta) {
	/* Obtencion datos receta */
	var datos;
	$.ajax({
		type: 'POST',
		url: "../buscadores/RecetaMedica_creaVentaControlador.php",
		data: { receta },
		async: false,
		success: function (response) {
			datos = JSON.parse(response);
			console.log("Ajax responde1");
			console.log(response);
			let repuesta = JSON.parse(response);
			console.log("Ajax responde2");
			console.log(repuesta);
			console.log("Otra pru");
			console.log(repuesta[0]['FechaEmision']);
			$('#fechaReceta').attr('value', repuesta[0]['FechaEmision']);
			$('#cantidadReceta').attr('value', repuesta[0]['Dosis']);
			$('#medicamento').attr('value', repuesta[0]['nombreComercial']);
			if (repuesta[1]['cantidad'] != null) {
				$('#disponibilidad').attr('value', repuesta[1]['cantidad']);
				$('#disponibilidad').removeClass('letraRoja');
				$('#disponibilidad').addClass('letraVerde');
			} else {
				$('#disponibilidad').attr('value', '0');
				$('#disponibilidad').removeClass('letraVerde');
				$('#disponibilidad').addClass('letraRoja');
			}
			$('#precio').attr('value', repuesta[2]['precioVenta']);
			$('#costo').attr('value', repuesta[2]['precioVenta'] * repuesta[0]['Dosis']);
		}
	});
}

function autocompletar() {
	const inputPaciente = document.querySelector('#receta_medica_reg');
	let indexFocus = -1;

	inputPaciente.addEventListener('input', function () {
		const nombrePaciente = this.value;//Hace referencia a inputPaciente

		if (!nombrePaciente) return false;
		cerrarLista();
		//crear la lista de sugerencias

		const divList = document.createElement('div');
		divList.setAttribute('id', this.id + '-lista-autocompletar');
		divList.setAttribute('class', 'lista-autocompletar-items');

		this.parentNode.appendChild(divList);

		//CONEXION A BD
		var serverTi = document.getElementById("serverTi").innerText;//Este elemento esta antes de la llamada al scrpt en el html
		httpRequest(serverTi + 'buscadores/RecetaMedica_creaVentaControlador.php?recetaMed_reg=' + nombrePaciente, function () {
			const arreglo = JSON.parse(this.responseText);

			//validar el arreglo vs el input

			if (arreglo.length == 0) return false;
			arreglo.forEach(item => {

				if (item.substr(0, nombrePaciente.length) == nombrePaciente) {
					const elementoLista = document.createElement('div');

					elementoLista.innerHTML = `<strong> ${item.substr(0, nombrePaciente.length)}</strong>${item.substr(nombrePaciente.length)}  `;;
					elementoLista.addEventListener('click', function () {
						inputPaciente.value = this.innerText;
						console.log("Mensaje de prueba");
						console.log("Primer parametro: " + inputPaciente.value.split("__")[0]);
						console.log("Segundo parametro: " + inputPaciente.value.split("__")[1]);
						console.log("Tercer parametro: " + inputPaciente.value.split("__")[2]);
						cerrarLista();
						solicitarDatos(inputPaciente.value.split("__")[1]);
						return false;
					});
					divList.appendChild(elementoLista);
				}
			});
		});
	});

	inputPaciente.addEventListener('click', function () {
		console.log("Paso click");
		const nombrePaciente = this.value;//Hace referencia a inputPaciente

		if (nombrePaciente) return false;
		cerrarLista();
		//crear la lista de sugerencias

		const divList = document.createElement('div');
		divList.setAttribute('id', this.id + '-lista-autocompletar');
		divList.setAttribute('class', 'lista-autocompletar-items');

		this.parentNode.appendChild(divList);

		//CONEXION A BD
		var serverTi = document.getElementById("serverTi").innerText;//Este elemento esta antes de la llamada al scrpt en el html
		httpRequest(serverTi + 'buscadores/RecetaMedica_creaVentaControlador.php?recetaMed_reg=' + nombrePaciente, function () {
			const arreglo = JSON.parse(this.responseText);

			//validar el arreglo vs el input

			if (arreglo.length == 0) return false;
			arreglo.forEach(item => {
				const elementoLista = document.createElement('div');

				elementoLista.innerHTML = `${item}`;
				console.log("Paso click");
				elementoLista.addEventListener('click', function () {
					inputPaciente.value = this.innerText;
					console.log("Mensaje de prueba");
					console.log("Primer parametro: " + inputPaciente.value.split("__")[0]);
					console.log("Segundo parametro: " + inputPaciente.value.split("__")[1]);
					console.log("Tercer parametro: " + inputPaciente.value.split("__")[2]);
					cerrarLista();
					solicitarDatos(inputPaciente.value.split("__")[1]);
					return false;
				});
				divList.appendChild(elementoLista);
			});
		});
	});

	inputPaciente.addEventListener('keydown', function (e) {
		const divList = document.querySelector('#' + this.id + '-lista-autocompletar');
		let items;

		if (divList) {//si existe divList
			items = divList.querySelectorAll('div');

			switch (e.keyCode) {
				case 40: //tecla abajo
					indexFocus++;
					if (indexFocus > items.length - 1) indexFocus = items.length - 1;
					break;

				case 38: //tecla arriba
					indexFocus--;
					if (indexFocus < 0) indexFocus = 0;
					break;

				case 13: //presionas enter
					e.preventDefault();
					items[indexFocus].click();//Ya configurado arriba con el event listener
					indexFocus = -1;
					break;

				case 8://Presionar boton backspace
					let med = $('#receta_medica_reg').val();
					if (med == "" || med.length <= 1) {
						$("#fechaReceta").attr("value", "");
						$("#medicamento").attr("value", "");
						$("#cantidadReceta").attr("value", "");
						$("#disponibilidad").attr("value", "");
						$("#precio").attr("value", "");
						$("#costo").attr("value", "");
						cerrarLista();
					}

					defult:
					break;
			}

			seleccionar(items, indexFocus);
			return false;
		}

	});

	/* document.addEventListener('click', function () {
		cerrarLista();
	}) */
	/* david */
	window.onclick = e => {
		if (!(e.target.parentElement.classList.contains("autocompletar"))) {
			cerrarLista();
		}
	}

}

function seleccionar(items, indexFocus) {
	if (!items || indexFocus == -1) return false;

	items.forEach(x => { x.classList.remove('autocompletar-active') });
	items[indexFocus].classList.add('autocompletar-active');
}

function cerrarLista() {
	const items = document.querySelectorAll('.lista-autocompletar-items');
	items.forEach(item => {
		item.parentNode.removeChild(item);
	});
	indexFocus = -1;
}

function httpRequest(url, callback) {
	const http = new XMLHttpRequest();
	http.open('GET', url);
	http.send();

	http.onreadystatechange = function () {
		if (this.readyState == 4 && this.status == 200) {
			callback.apply(http);
		}
	}
}
autocompletar();
