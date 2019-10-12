
  
$(function() {

    //$(".form-group.required").find("label.control-label").append('<span Class="required">*</span>');
    ;( function( $, window, document, undefined )
    {
        $( '.inputfile' ).each( function()
        {
            var $input   = $( this ),
                $label   = $input.next( 'label' ),
                labelVal = $label.html();
    
            $input.on( 'change', function( e )
            {
                var fileName = '';
    
                if( this.files && this.files.length > 1 )
                    fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
                else if( e.target.value )
                    fileName = e.target.value.split( '\\' ).pop();
    
                if( fileName )
                    $label.find( 'span' ).html( fileName );
                else
                    $label.html( labelVal );
            });
    
            // Firefox bug fix
            $input
            .on( 'focus', function(){ $input.addClass( 'has-focus' ); })
            .on( 'blur', function(){ $input.removeClass( 'has-focus' ); });
        });
    })( jQuery, window, document );
       
    });
    // $("#next_step").each(function(){
        
    //     console.log($(this).attr('href'));
    // });
    // $('#check_validation').click(function(){

    // });
    