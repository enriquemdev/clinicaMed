$('input[id=cedula_reg]').keypress(function(){
    var rawNumbers = $(this).val().replace(/-/g,'');
    var cardLength = rawNumbers.length;

    if(cardLength !==0 && cardLength <=3 && cardLength % 3 == 0){
            $(this).val($(this).val()+'-');  
    }
    if(cardLength ==9 ){
        
        $(this).val($(this).val()+'-');  
    
    }
    /*Posible validación
    if(cardLength==12){
        var ultimo = rawNumbers.charAt(cardLength -1);
        var i =0;
        for(i; i<=9; i++){
            if(ultimo==i){
                alert("La cagaste Jajaja" + $(this).val())
            }
        }
    }
       */ 

    
   });
   //Formato de empleados
   $('input[id=empleado_cedula]').keypress(function(){
    var rawNumbers = $(this).val().replace(/-/g,'');
    var cardLength = rawNumbers.length;

    if(cardLength !==0 && cardLength <=3 && cardLength % 3 == 0){
            $(this).val($(this).val()+'-');  
    }
    if(cardLength ==9 ){
        
        $(this).val($(this).val()+'-');  
    
    }
    
   });

   //Formato de familiares de empleado
   $('input[id=cedula_familiar]').keypress(function(){
    var rawNumbers = $(this).val().replace(/-/g,'');
    var cardLength = rawNumbers.length;

    if(cardLength !==0 && cardLength <=3 && cardLength % 3 == 0){
            $(this).val($(this).val()+'-');  
    }
    if(cardLength ==9 ){
        
        $(this).val($(this).val()+'-');  
    
    }
    
   });
