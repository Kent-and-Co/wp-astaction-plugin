jQuery().ready( function( $ ) {
  $( document ).on( 'change', 'input[id^=\'in-casting-talent-\']', function() {
    toggleCastingMeta( this );
    checkCastingMeta();
  });

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
    var parent = $( event )
      .parent()
      .html();
    var term = parent.replace( /<input(.*)> /g, '' ).replace( /\s+/g, '' );

    var checked = $( event ).prop( 'checked' );
    var classname = '.' + term;

    if ( checked ) {
      $( classname ).show();
    } else {
      $( classname ).hide();
    }
  }

  function checkCastingMeta() {
    var flag = false;
    $( 'input[id^=\'in-casting-talent-\']' ).each( function( index1, elm1 ) {
      var checked = $( elm1 ).prop( 'checked' );
      var parent = $( elm1 )
        .parent()
        .html();
      var term = parent.replace( /<input(.*)> /g, '' );

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
