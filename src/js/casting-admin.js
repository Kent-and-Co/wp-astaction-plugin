jQuery().ready( function( $ ) {
  $( document ).on(
    'change',
    'input[id^=\'inspector-checkbox-control-\']',
    function() {
      toggleCastingMeta( this );
      checkCastingMeta();
    }
  );

  $( document ).on( 'change', '#casting_type', function() {
    var val = $( this ).val();
    var options = $( '#casting_type option' );

    options.each( function( index, elm ) {
      if ( $( elm ).val() == val ) {
        $( '.' + $( elm ).val() ).show();
      } else {
        $( '.' + $( elm ).val() ).hide();
      }
    });
  });

  function toggleCastingMeta( event ) {
    var id = $( event ).attr( 'id' );
    var term = $( 'label[for=' + id + ']' ).html();

    var checked = $( event ).prop( 'checked' );
    var classname = '.' + term.replace( /\s+/g, '' );

    if ( checked ) {
      $( classname ).show();
    } else {
      $( classname ).hide();
    }
  }

  function checkCastingMeta() {
    var flag = false;
    $( 'input[id^=\'inspector-checkbox-control-\']' ).each( function( index1, elm1 ) {
      var checked = $( elm1 ).prop( 'checked' );
      var id = $( elm1 ).attr( 'id' );
      var term = $( 'label[for=' + id + ']' ).html();

      if ( checked ) {
        $( '.casting_talent_name' ).each( function( index2, elm2 ) {
          if ( $( elm2 ).html() == term ) {
            flag = true;
          }
        });
      }
    });
    if ( flag ) {
      $( '.casting_cap1' ).show();
      $( '.casting_cap2' ).hide();
    } else {
      $( '.casting_cap1' ).hide();
      $( '.casting_cap2' ).show();
    }
  }
});
