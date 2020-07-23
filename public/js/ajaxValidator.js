// $(function()
// {
//     // AJAX FORM
//     $('#login-form').submit(function(e) 
//     {
//         e.preventDefault();
//         // var url = document.querySelector('#yourid').value        
//         $('.invalid-feedback').empty();
//         var postdata = $('#login-form').serialize();
        
//         $.ajax
//         ({
//             type: 'POST',
//             url: url,
//             data: postdata,
//             dataType: 'json',  
//             //beforeSend:function(data) { alert(url) },          
//             success: function(data) 
//             {   
//                 // alert('hello');              
//                 if(!data.success) 
//                 {
//                     // alert('hello');
//                     // console.log(data.username_err);
//                     // console.log(data.password_err);
//                     if(data.username_err) $( "#username" ).addClass( "is-invalid" );
//                     else $( "#username" ).removeClass( "is-invalid" );
//                     if(data.password_err) $( "#password" ).addClass( "is-invalid" );
//                     else $( "#password" ).removeClass( "is-invalid" );

//                     $('#username + .invalid-feedback').html(data.username_err);
//                     $('#password + .invalid-feedback').html(data.password_err);
//                     $('#recaptcha_err').html(data.recaptcha_err);
//                 }            
//             },
//             error: function() { console.log('ERROR') }
//         });
//     });
// })