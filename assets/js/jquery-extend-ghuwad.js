(function ($) {
    $.assignTab = function(){
        jQuery(document).on('click', '.gnav > a', function(event) {
            jQuery('.g-success-msg,.g-error-msg').fadeIn('slow', function(){jQuery(this).remove();});
            jQuery('> a',jQuery(this).parent()).each(function(index, ele){
                id = '#gtab-'+jQuery(ele).data('tab');
                jQuery(id).hide().removeClass('open');
                jQuery(this).removeClass('active');
            });
            id = '#gtab-'+jQuery(this).data('tab');
            jQuery(id).slideDown();
            jQuery(this).addClass('active');
            if(jQuery(this).hasClass('ghas-sub')) {
                jQuery(id+' .gnav:eq(0) > a:eq(0)').trigger('click');
            }
        });
    }
}(jQuery));