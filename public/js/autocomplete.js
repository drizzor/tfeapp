$(document).ready(function(){
    $('#searchBox').keyup(function(){
        let query = $("#searchBox").val();        
        if(query.length > 2)
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

    $(document).on('click', '.city', function()
    {
        var contry = $(this).text();
        $("#searchBox").val(contry);
        $("#response").html("");        
    });
});