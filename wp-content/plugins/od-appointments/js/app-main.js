jQuery(".month").change(function(){
        
    var app_month = jQuery(this).val();
       
    jQuery.post( 
        ajaxurl,
            {   
                'action': 'define_profile_calendar',
                'app_month': app_month,
                'app_year' : app_year
            }, 
            function(response){

               jQuery('.my-appointments').replaceWith(response.substr(response.length-1, 1) === '0'? response.substr(0, response.length-1) : response);
    });          
});