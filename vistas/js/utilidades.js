
//FUNCIÓN PARA BUSCAR EN TIEMPO REAL PARA PERSONA
$(document).ready(function(){
    $("#live_search").keyup(function(){
        var input = $(this).val(); //Aquí se guarda la info del input dentro de la variable input
        
        if(input!=""){
            $("#searchresult").css("display","block");
            
            $.ajax({

                url:"../Live_search/buscadorpersona.php",
                method:"POST",
                data:{input:input},

                success:function(data){
                    $("#searchresult").html(data);
                }
            });
        }else{
            $("#searchresult").css("display","none");
        }
    });
});

$(document).ready(function(){
    console.log("Aver")
    $("#live_search_Responsable").keyup(   
    function(){
        var input = $(this).val(); //Aquí se guarda la info del input dentro de la variable input
        
        if(input!=""){
            $("#responsablesResult").css("display","block");
            
            $.ajax({

                url:"../Live_search/buscadortutor.php",
                method:"POST",
                data:{input:input},

                success:function(data){
                    $("#responsablesResult").html(data);
                }
            });
        }else{
            $("#searchresult").css("display","none");
        }
    });
} );

//función cuando se selecciona persona


//COMPLEMENTO DE MODAL Nota! dió problema si lo ponía de primero, dejar de último
const myModal = document.getElementById('myModal')
		const myInput = document.getElementById('myInput')

		myModal.addEventListener('shown.bs.modal', () => {
		myInput.focus()
		})
