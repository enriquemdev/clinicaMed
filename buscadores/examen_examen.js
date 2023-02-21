function autocompletar() {
	const inputPaciente = document.querySelector('#codigo_examen');
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
		httpRequest(serverTi + 'buscadores/examen_examenControlador.php?text=' + nombrePaciente, function () {
			const arreglo = JSON.parse(this.responseText);

			//validar el arreglo vs el input
			//if(arreglo.length == 0) return false;
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
						cerrarLista();
						return false;
					});
					divList.appendChild(elementoLista);
				}
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

					defult:
					break;
			}

			seleccionar(items, indexFocus);
			return false;
		}

	});

	document.addEventListener('click', function () {
		cerrarLista();
	})

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

/*EXPERIMENTAL******************************************************************************************************************* */
/*
function autocompletar(){
	const inputPaciente = document.querySelector('#proveedor_reg');
	const tipos = document.querySelector('#tipos');
	let indexFocus=-1;

	
	tipos.addEventListener('click', autocomplete2, false);
	inputPaciente.addEventListener('input', autocomplete, false);

	//inputPaciente.addEventListener('input', function(){
		function autocomplete(event){
			console.log(event.type);
			var tipo = 0;
			if (event.type == 'input')
			{
				tipo = 1;
			}
			if(event.type == 'click') {
				tipo = 2;
			}
		const nombrePaciente = this.value;//Hace referencia a inputPaciente
		
		
		if(!nombrePaciente) return false;
		cerrarLista();
		//crear la lista de sugerencias

		const divList= document.createElement('div');
		divList.setAttribute('id', this.id + '-lista-autocompletar');
		divList.setAttribute('class', 'lista-autocompletar-items');

		this.parentNode.appendChild(divList);

	//CONEXION A BD
	var serverTi = document.getElementById("serverTi").innerText;//Este elemento esta antes de la llamada al scrpt en el html
	httpRequest(serverTi+'buscadores/nombreProveedor_creaCompraControlador.php?proveedor_reg='+nombrePaciente+'&tipo='+tipo, function(){
		const arreglo= JSON.parse(this.responseText);

		//validar el arreglo vs el input

				if(arreglo.length == 0) return false;
				arreglo.forEach(item => {

					if(tipo == 1)
					{
						if(item.substr(0, nombrePaciente.length) == nombrePaciente){
							const elementoLista = document.createElement('div');
	
							elementoLista.innerHTML=`<strong> ${item.substr(0,nombrePaciente.length)}</strong>${item.substr(nombrePaciente.length)}  `;
							elementoLista.addEventListener('click', function(){
								inputPaciente.value= this.innerText;	
								cerrarLista();
								return false;
							});
							divList.appendChild(elementoLista);
						}
					}
					else {
						
						const elementoLista = document.createElement('div');
						elementoLista.innerHTML=`${item}  `;;
						divList.appendChild(elementoLista);
					}
				});
			});
		
		}//);

		function autocomplete2(event){
			console.log(event.type);
			var tipo = 0;
			if (event.type == 'input')
			{
				tipo = 1;
			}
			if(event.type == 'click') {
				tipo = 2;
			}
		const nombrePaciente = this.value;//Hace referencia a inputPaciente
		
		const divList= document.createElement('div');
		divList.setAttribute('id', this.id + '-lista-autocompletar');
		divList.setAttribute('class', 'lista-autocompletar-items');

		this.parentNode.appendChild(divList);

	//CONEXION A BD
	var serverTi = document.getElementById("serverTi").innerText;//Este elemento esta antes de la llamada al scrpt en el html
	httpRequest(serverTi+'buscadores/nombreProveedor_creaCompraControlador.php?proveedor_reg='+nombrePaciente+'&tipo='+tipo, function(){
		const arreglo= JSON.parse(this.responseText);

		//validar el arreglo vs el input

				if(arreglo.length == 0) return false;
				arreglo.forEach(item => {

					if(tipo == 1)
					{
						if(item.substr(0, nombrePaciente.length) == nombrePaciente){
							const elementoLista = document.createElement('div');
	
							elementoLista.innerHTML=`<strong> ${item.substr(0,nombrePaciente.length)}</strong>${item.substr(nombrePaciente.length)}  `;;
							elementoLista.addEventListener('click', function(){
								inputPaciente.value= this.innerText;	
								cerrarLista();
								return false;
							});
							divList.appendChild(elementoLista);
						}
					}
					else {
						
						const elementoLista = document.createElement('div');
						elementoLista.innerHTML=`${item}  `;;
						divList.appendChild(elementoLista);
					}
				});
			});
		
		}//);

		

	inputPaciente.addEventListener('keydown', function(e){
		const divList= document.querySelector('#'+this.id+'-lista-autocompletar');
		let items;

		if(divList){//si existe divList
			items =divList.querySelectorAll('div');

			switch(e.keyCode){
				case 40: //tecla abajo
				indexFocus++;
				if(indexFocus > items.length-1) indexFocus=items.length -1;
				break;

				case 38: //tecla arriba
				indexFocus--;
				if(indexFocus <	 0) indexFocus=0;
				break;

				case 13: //presionas enter
				e.preventDefault();
				items[indexFocus].click();//Ya configurado arriba con el event listener
				indexFocus=-1;
				break;

				defult:
				break;
			}

			seleccionar(items, indexFocus);
			return false;
		}

	});

	document.addEventListener('click', function(){
		cerrarLista();
	})

}

function seleccionar(items, indexFocus){
	if(!items || indexFocus == -1) return false;

	items.forEach(x => {x.classList.remove('autocompletar-active')});
	items[indexFocus].classList.add('autocompletar-active');
}

function cerrarLista(){
	const items= document.querySelectorAll('.lista-autocompletar-items');
	items.forEach(item =>{
		item.parentNode.removeChild(item);
	});
	indexFocus=-1;
}

function httpRequest(url, callback){
	const http= new XMLHttpRequest();
	http.open('GET', url);
	http.send();

	http.onreadystatechange = function(){
		if(this.readyState==4 && this.status == 200){
			callback.apply(http);
		}
	}
}

autocompletar();
*/