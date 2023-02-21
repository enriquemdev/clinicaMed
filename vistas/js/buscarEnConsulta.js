function autocompletar(arreglo){
	const inputPaciente = document.querySelector('#consulta_paciente_reg');
	let indexFocus=-1;

	inputPaciente.addEventListener('input', function(){
		const nombrePaciente = this.value;//Hace referencia a inputPaciente

		if(!nombrePaciente) return false;
		cerrarLista();
		//crear la lista de sugerencias

		const divList= document.createElement('div');
		divList.setAttribute('id', this.id + '-lista-autocompletar');
		divList.setAttribute('class', 'lista-autocompletar-items');

		this.parentNode.appendChild(divList);

	//CONEXION A BD
	httpRequest('http://localhost/ClinicaMed/buscadores/BusquedasControlador.php?consulta_paciente_reg='+nombrePaciente, function(){
		//console.log(this.responseText);
		const arreglo= JSON.parse(this.responseText);
		console.log(arreglo);
		});

		//validar el arreglo vs el input

		if(arreglo.length == 0) return false;
		arreglo.forEach(item => {

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
		});
	});

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

autocompletar(['ganso', 'loro', 'lolo', 'maestro']);