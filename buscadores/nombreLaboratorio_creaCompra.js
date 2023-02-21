
function solicitarDatos(medicamento, laboratorio) {
	/* Obtencion datos receta */
	$.ajax({
		type: 'POST',
		url: "../buscadores/nombreLaboratorio_creaCompraControlador.php",
		data: { medicamento, laboratorio },
		async: false,
		success: function (response) {
			let repuesta = JSON.parse(response);
			$('#proveedor_reg').val(repuesta[0]['nombreProveedor'] + "__" + repuesta[0]['proveedor'] + "__" + repuesta[0]['tiempoEntrega']);
		}
	});
}

function autocompletar() {
	const inputPaciente = document.querySelector('#item_laboratorio');
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

		let med = $('#medicamento').val().split("__")[1];

		//CONEXION A BD
		var serverTi = document.getElementById("serverTi").innerText;//Este elemento esta antes de la llamada al scrpt en el html
		httpRequest(serverTi + 'buscadores/nombreLaboratorio_creaCompraControlador.php?laboratorio_reg=' + nombrePaciente + '&medicamento=' + med, function () {
			const arreglo = JSON.parse(this.responseText);

			if (arreglo.length == 0) {
				const elementoLista = document.createElement('div');
				elementoLista.innerHTML = `<strong>No hay resultados para esta búsqueda</strong>`;
				divList.appendChild(elementoLista);
			}
			arreglo.forEach(item => {

				if ((item.substr(0, nombrePaciente.length)).toUpperCase() == nombrePaciente.toUpperCase()) {
					const elementoLista = document.createElement('div');

					elementoLista.innerHTML = `<strong> ${item.substr(0, nombrePaciente.length)}</strong>${item.substr(nombrePaciente.length)}  `;;
					elementoLista.addEventListener('click', function () {
						inputPaciente.value = this.innerText;
						/* Recuperamos el med */
						let medicamento = $('#medicamento').val().split("__")[1];
						/* Recuperamos el lab */
						let laboratorio = inputPaciente.value.split("__")[1];
						cerrarLista();
						solicitarDatos(medicamento, laboratorio);
						$('#proveedor_reg').removeAttr("readonly");
						$('#cantidad').focus();
						return false;
					});
					divList.appendChild(elementoLista);
				}
			});
		});

	});

	inputPaciente.addEventListener('focus', function () {
		if ($('#item_laboratorio').is('[readonly="false"]') || !$('#item_laboratorio').is('[readonly]')) {
			const nombrePaciente = this.value;//Hace referencia a inputPaciente

			/* if (!nombrePaciente) return false;
			cerrarLista(); */
			if (nombrePaciente) return false;
			cerrarLista();
			//crear la lista de sugerencias

			const divList = document.createElement('div');
			divList.setAttribute('id', this.id + '-lista-autocompletar');
			divList.setAttribute('class', 'lista-autocompletar-items');

			this.parentNode.appendChild(divList);

			let med = $('#medicamento').val().split("__")[1];

			//CONEXION A BD
			var serverTi = document.getElementById("serverTi").innerText;//Este elemento esta antes de la llamada al scrpt en el html
			httpRequest(serverTi + 'buscadores/nombreLaboratorio_creaCompraControlador.php?medicamento=' + med, function () {
				const arreglo = JSON.parse(this.responseText);

				if (arreglo.length == 0) {
					const elementoLista = document.createElement('div');
					elementoLista.innerHTML = `<strong>No hay resultados para esta búsqueda</strong>`;
					divList.appendChild(elementoLista);
				}
				arreglo.forEach(item => {
					const elementoLista = document.createElement('div');

					elementoLista.innerHTML = `${item}`;
					elementoLista.addEventListener('click', function () {
						inputPaciente.value = this.innerText;
						/* Recuperamos el med */
						let medicamento = $('#medicamento').val().split("__")[1];
						/* Recuperamos el lab */
						let laboratorio = inputPaciente.value.split("__")[1];
						cerrarLista();
						solicitarDatos(medicamento, laboratorio);
						$('#proveedor_reg').removeAttr("readonly");
						$('#cantidad').focus();
						return false;
					});
					divList.appendChild(elementoLista);
				});
			});
		}
	});
	inputPaciente.addEventListener('click', function () {
		if ($('#item_laboratorio').is('[readonly="false"]') || !$('#item_laboratorio').is('[readonly]')) {
			const nombrePaciente = this.value;//Hace referencia a inputPaciente

			/* if (!nombrePaciente) return false;
			cerrarLista(); */
			if (nombrePaciente) return false;
			cerrarLista();
			//crear la lista de sugerencias

			const divList = document.createElement('div');
			divList.setAttribute('id', this.id + '-lista-autocompletar');
			divList.setAttribute('class', 'lista-autocompletar-items');

			this.parentNode.appendChild(divList);

			let med = $('#medicamento').val().split("__")[1];

			//CONEXION A BD
			var serverTi = document.getElementById("serverTi").innerText;//Este elemento esta antes de la llamada al scrpt en el html
			httpRequest(serverTi + 'buscadores/nombreLaboratorio_creaCompraControlador.php?medicamento=' + med, function () {
				const arreglo = JSON.parse(this.responseText);

				if (arreglo.length == 0) {
					const elementoLista = document.createElement('div');
					elementoLista.innerHTML = `<strong>No hay resultados para esta búsqueda</strong>`;
					divList.appendChild(elementoLista);
				}
				arreglo.forEach(item => {
					const elementoLista = document.createElement('div');

					elementoLista.innerHTML = `${item}`;
					elementoLista.addEventListener('click', function () {
						inputPaciente.value = this.innerText;
						/* Recuperamos el med */
						let medicamento = $('#medicamento').val().split("__")[1];
						/* Recuperamos el lab */
						let laboratorio = inputPaciente.value.split("__")[1];
						cerrarLista();
						solicitarDatos(medicamento, laboratorio);
						$('#proveedor_reg').removeAttr("readonly");
						$('#cantidad').focus();
						return false;
					});
					divList.appendChild(elementoLista);
				});
			});
		}
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
					let med = $('#item_laboratorio').val();
					if (med == "" || med.length <= 1) {
						$('#proveedor_reg').val("");
						$('#proveedor_reg').attr("readonly", "true");
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

}

function seleccionar(items, indexFocus) {
	if (!items || indexFocus == -1) return false;

	items.forEach(x => { x.classList.remove('autocompletar-active') });
	items[indexFocus].classList.add('autocompletar-active');
}

function cerrarLista() {
	console.log("Paso cerrar lab");
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