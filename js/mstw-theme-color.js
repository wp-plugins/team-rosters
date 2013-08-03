jQuery(document).ready(function($) {
    $('#colorpicker').hide();
    $('#colorpicker').farbtastic('#color');

    $('#color').click(function() {
        $('#colorpicker').fadeIn();
    });

    $(document).mousedown(function() {
        $('#colorpicker').each(function() {
            var display = $(this).css('display');
            if ( display == 'block' )
                $(this).fadeOut();
        });
    });
});

<script type="text/javascript">
 
  jQuery(document).ready(function() {
    jQuery('#ilctabscolorpicker').hide();
    jQuery('#ilctabscolorpicker').farbtastic("#color");
    jQuery("#color").click(function(){jQuery('#ilctabscolorpicker').slideToggle()});
  });
 
</script>