jQuery(document).ready(function(){
    jQuery('input.typeahead').typeahead({
        name: 'typeahead',
        remote:'/wp-content/plugins/od-user-search/includes/od-search-data.php?key=%QUERY',
        limit : 10
    });
});