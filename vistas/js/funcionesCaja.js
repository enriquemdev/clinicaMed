//VARIABLES INICIALES
var totalServicios = document.getElementById("totalServicios");
var totalRebaja = document.getElementById("totalRebaja");
var totalValor = document.getElementById("totalTotal");
var totalFinal = document.getElementById("totalFinal");

var preciosServicios = document.querySelectorAll(".preciosServicios");
var inputsDeRebaja = document.querySelectorAll(".inputRebajaCaja");
var totalesCaja = document.querySelectorAll(".totalCaja");
var inputsDeCobro = document.querySelectorAll(".inputCobroCaja");
var checkboxsCaja = document.querySelectorAll(".checkboxsCaja");

var cantidadServiciosTotal = 0, cantidadRebajaTotal = 0, cantidadCobroTotal = 0, cantidadValorTotal = 0;

//inputsDeRebaja.onkeyPress = calcularRebajaTotal();

//FUNCIONES

function calcularServiciosTotal()
{
    cantidadServiciosTotal = 0;
    for (var precioServicio of preciosServicios)
    {
        cantidadServiciosTotal += parseFloat(precioServicio.textContent);
    }

    totalServicios.value = cantidadServiciosTotal;
}

function calcularRebajaTotal()
{
    cantidadRebajaTotal = 0;
    for (var inputRebaja of inputsDeRebaja)
    {
        if (inputRebaja.value == '' || (inputRebaja.value).trim() == '.' || parseFloat(inputRebaja.value) < 0)
        {
            inputRebaja.value = '0.00';
            //calcularValorTotal();        
        }
        cantidadRebajaTotal += parseFloat(inputRebaja.value);
    }

    totalRebaja.value = cantidadRebajaTotal;
}

function calcularValorTotal()
{
    cantidadValorTotal = 0;
    for (var totalCaja of totalesCaja)
    {
        //Calcular el valor individual del input cobro
        //console.log(inputsDeRebaja[parseInt(inputCobro.contador)+1].value);
        //console.log(parseFloat(inputsDeRebaja[(parseInt(inputCobro.getAttribute('contador')))].value));
        totalCaja.textContent = parseFloat(preciosServicios[parseInt(totalCaja.getAttribute('contador'))-1].textContent) - parseFloat(inputsDeRebaja[(parseInt(totalCaja.getAttribute('contador')))-1].value);

        //Suma final + el calculo del input individual
        cantidadValorTotal += parseFloat(totalCaja.textContent);
    }

    totalValor.value = cantidadValorTotal;
}

function calcularCobroTotalInicial()
{
    cantidadCobroTotal = 0;
    for (var inputCobro of inputsDeCobro)
    {
        //Calcular el valor individual del input cobro
        //console.log(inputsDeRebaja[parseInt(inputCobro.contador)+1].value);
        //console.log(parseFloat(inputsDeRebaja[(parseInt(inputCobro.getAttribute('contador')))].value));
        //inputCobro.value = parseFloat(preciosServicios[parseInt(inputCobro.getAttribute('contador'))-1].textContent) - parseFloat(inputsDeRebaja[(parseInt(inputCobro.getAttribute('contador')))-1].value);

        //Suma final + el calculo del input individual
        cantidadCobroTotal += parseFloat(inputCobro.value);
    }

    totalFinal.value = cantidadCobroTotal;
}

function calcularCobroTotalFinal()
{
    cantidadCobroTotal = 0;
    for (var inputCobro of inputsDeCobro)
    {
        //Suma final + el calculo del input individual
        cantidadCobroTotal += parseFloat(inputCobro.value);
    }

    totalFinal.value = cantidadCobroTotal;
}

function validar2Decimales(event)
{
    var valorInput = event.target.value;

    if (valorInput.includes('.'))
    {
        if (valorInput.indexOf('.') < (valorInput.length - 3))
        {
            event.target.value = event.target.value.substr(0,valorInput.length-1);
        }
    }
}

function validarRebajaMenorTotal(event)
{
    var valorInput = event.target.value;

    if (valorInput >= (parseFloat(preciosServicios[parseInt(event.target.getAttribute('contador'))-1].textContent)))
    {
        event.target.value = parseFloat(preciosServicios[parseInt(event.target.getAttribute('contador'))-1].textContent);
        event.target.style.color = 'red';
    }
    else
    {
        event.target.style.color = 'black';
    }
}

// function validarMontoCobro(event)
// {
//     var valorInput = event.target.value;

//     if (valorInput > (parseFloat(preciosServicios[parseInt(event.target.getAttribute('contador'))-1].textContent) - parseFloat(inputsDeRebaja[(parseInt(event.target.getAttribute('contador')))-1].value)))
//     {
//         event.target.value = (parseFloat(preciosServicios[parseInt(event.target.getAttribute('contador'))-1].textContent) - parseFloat(inputsDeRebaja[(parseInt(event.target.getAttribute('contador')))-1].value));
//         //event.target.style.color = 'red';
//     }

// }
function validarMontoCobro(event)
{
    
    var contador = (parseInt(event.target.getAttribute('contador'))-1);
    var totalCaja = totalesCaja[contador];
    var inputCobro = inputsDeCobro[contador];

    if (parseFloat(inputCobro.value) > parseFloat(totalCaja.textContent))//(parseFloat(preciosServicios[parseInt(event.target.getAttribute('contador'))-1].textContent) - parseFloat(inputsDeRebaja[(parseInt(event.target.getAttribute('contador')))-1].value))
    {
        inputCobro.value = parseFloat(totalCaja.textContent);//(parseFloat(preciosServicios[parseInt(event.target.getAttribute('contador'))-1].textContent) - parseFloat(inputsDeRebaja[(parseInt(event.target.getAttribute('contador')))-1].value));
        //event.target.style.color = 'red';
    }

}

function quitarCerosSiClic(event)
{
    var input = event.target;
    if (parseInt(input.value) == 0)
    {
        input.value = "";
    }
}

function cobrarCheckboxs(event)
{
    let contador3 = 0;
    //event.target.getAttribute('contador');
    cantidadCobroTotal = 0;
    for (var inputCobro of inputsDeCobro)
    {
        if (inputCobro.value == '' || (inputCobro.value).trim() == '.' || parseFloat(inputCobro.value) < 0)
            {
                inputCobro.value = '0.00';
                //calcularValorTotal();        
            }
        // if (checkboxsCaja[parseInt(event.target.getAttribute('contador'))-1].checked)
        // {
        //     cantidadCobroTotal += parseFloat(inputCobro.value);
        //     console.log((parseInt(event.target.getAttribute('contador'))-1)+" "+(checkboxsCaja[parseInt(event.target.getAttribute('contador'))-1].checked));
        // }

        if (checkboxsCaja[contador3].checked)
        {
            cantidadCobroTotal += parseFloat(inputCobro.value);
            //console.log((parseInt(event.target.getAttribute('contador'))-1)+" "+(checkboxsCaja[parseInt(event.target.getAttribute('contador'))-1].checked));
        }
        contador3++;
    }

    totalFinal.value = cantidadCobroTotal;
}


//SENSORES DE EVENTOS

//rebajas
for (var inputRebaja of inputsDeRebaja)
    {        
        // inputRebaja.addEventListener("change",function() {
        //     //Aquí la función que se ejecutará cuando se dispare el evento
        //     calcularRebajaTotal(); //En este caso alertaremos el texto del cliqueado
        //     calcularValorTotal();
            
        //     validarMontoCobro(e);
        //     calcularCobroTotalInicial();

        //  });

         //Evento que le pone el valor por defecto a los campos si estan vacios.
         inputRebaja.addEventListener('focusout', (event) => {
            if (event.target.value == '' || (event.target.value).trim() == '.')
            {
                event.target.value = '0.00';
                
            }
            calcularRebajaTotal();
            calcularValorTotal();
            
            validarMontoCobro(event);
            //calcularCobroTotalInicial();
            cobrarCheckboxs(event);//En vez de calcularCobroTotalFinal
          });

          inputRebaja.addEventListener('mouseleave', (event) => {
            if (event.target.value == '' || (event.target.value).trim() == '.')
            {
                event.target.value = '0.00';
                
            }
            calcularRebajaTotal();
            calcularValorTotal();
            
            validarMontoCobro(event);
            //calcularCobroTotalInicial();
            cobrarCheckboxs(event);//En vez de calcularCobroTotalFinal
          });

          inputRebaja.addEventListener('input', (event) => {
            validar2Decimales(event);
            calcularRebajaTotal();//new
            calcularValorTotal();//new
            validarRebajaMenorTotal(event);
            
            validarMontoCobro(event);
            //calcularCobroTotalInicial();
            cobrarCheckboxs(event);//En vez de calcularCobroTotalFinal
          });

          inputRebaja.addEventListener('click', (event) => {
            quitarCerosSiClic(event);
            cobrarCheckboxs(event);
          });
    }

    //cobros
    for (var inputCobro of inputsDeCobro)
    {        
        // inputCobro.addEventListener("change",function() {
        //     //Aquí la función que se ejecutará cuando se dispare el evento
        //     //calcularValorTotal();
        //     calcularCobroTotalFinal(); //En este caso alertaremos el texto del cliqueado
        //     validarMontoCobro(e);
        //     //validarMontoCobro();
        //  });

         //Evento que le pone el valor por defecto a los campos si estan vacios.
         inputCobro.addEventListener('focusout', (event) => {
            if (event.target.value == '' || (event.target.value).trim() == '.'|| parseFloat(event.target.value) < 0)
            {
                event.target.value = '0.00';
                //calcularValorTotal();        
            }
            //calcularCobroTotalFinal();
            cobrarCheckboxs(event);//En vez de calcularCobroTotalFinal
            
          });
          inputCobro.addEventListener('mouseleave', (event) => {
            if (event.target.value == '' || (event.target.value).trim() == '.' || parseFloat(event.target.value) < 0)
            {
                event.target.value = '0.00';
                //calcularValorTotal();        
            }
            //calcularCobroTotalFinal();
            cobrarCheckboxs(event);//En vez de calcularCobroTotalFinal
          });

          inputCobro.addEventListener('input', (event) => {
            if (event.target.value == '' || (event.target.value).trim() == '.' || parseFloat(event.target.value) < 0)
            {
                event.target.value = '0.00';
                //calcularValorTotal();        
            }
            validar2Decimales(event);
            validarMontoCobro(event);
            //calcularCobroTotalInicial();
            cobrarCheckboxs(event);//En vez de calcularCobroTotalFinal
            //quitarCerosSiClic(event);

          });

          inputCobro.addEventListener('click', (event) => {
            quitarCerosSiClic(event);
          });
    }

    for (var checkboxCaja of checkboxsCaja)
    { 
        checkboxCaja.addEventListener('click', (event) => {
            cobrarCheckboxs(event);
          });
    }

    //CALCULOS INICIALES
calcularServiciosTotal();
calcularRebajaTotal();
calcularValorTotal();
calcularCobroTotalInicial();

//HAY QUE CREAR OTRA COLUMA EN LA TABLA QUE MUESTRE EL TOTAL BRUTO, ANTES DE HAHCER EL COBRO ACTUAL