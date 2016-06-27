jQuery(document).ready(function(){
    jQuery('input.typeahead').typeahead({
        name: 'typeahead',
        remote:'/wp-content/plugins/od-user-search/includes/od-search-data-pro.php?key=%QUERY',
        limit : 10
    });
});

jQuery(document).ready(function() {
    //Show modal box
    //Hide modal box
    jQuery('#closeSearchModal').click(
        
        function() {
            jQuery('.od-searchBoxWrap').hide();
        }
    );
});