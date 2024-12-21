jQuery(function($) {
    $(document).ready(function() {

        // Add ID to Search Field
        $('.et_pb_search').each(function(){
            $(this).find('input.et_pb_s').each(function(){
                $(this).attr('id', 's');
            });
        });
        // Add Title to Nav Arrows
        $("a.et-pb-arrow-prev").each(function(){
            $(this).attr('aria-label', 'Zurück');
        });

        $("a.et-pb-arrow-next").each(function(){
            $(this).attr('aria-label', 'Weiter');
        });
    });
}); 