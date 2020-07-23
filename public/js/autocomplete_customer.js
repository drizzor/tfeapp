$(document).ready(function(){
    $('#searchBox').keyup(function(){
        let query = $("#searchBox").val();        
        if(query.length > 1)
        {           
            $.ajax
            (
                { //Création d'un objet JS                
                    url: urlAuto,
                    method:'POST',
                    data: {
                        search: 1,
                        q: query
                    },
                    success: function(data)	{     
                        $("#response").html(data);
                    },
                    dataType: 'text'                    
                }                
            );
        }
    });

    $(document).on('click', '.customer', function()
    {
        var customer = $(this).text();
        $("#searchBox").val(customer);
        $("#response").html("");        
    });
});