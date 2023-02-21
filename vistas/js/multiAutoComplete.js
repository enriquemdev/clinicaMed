$(document).ready(function(){
      
    $('#search_data').tokenfield({
        autocomplete :{
            source: function(request, response)
            {
                jQuery.get('../buscadores/multiSintomas.php', {
                    query : request.term
                }, function(data){
                    data = JSON.parse(data);
                    response(data);
                });
            },
            delay: 100
        }
    });

    $('#search').click(function(){
        $('#country_name').val($('#search_data').val());
    });

  });