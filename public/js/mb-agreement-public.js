(function( $ ) {
	'use strict';

	
	 $(function() {
	    $( "body" ).on('click', "#label_1_7_1 a", function(e) {
			e.preventDefault();
			$('.membership-agreement-text-area').slideToggle(200);
		})
	 });

})( jQuery );
