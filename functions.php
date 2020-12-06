<?php

// GRAVITY FORMS CODE TO CHANGE CONSENT FORM FIELD TO BE WITHIN AN ACCRODION
// DON'T FORGET TO CHANGE THE FORM ID BELOW

add_filter( 'gform_pre_render', 'gaws_consent_field', 10, 5 );
function gaws_consent_field( $form ) {

//Change to form ID
	if( $form["id"] != 0 ){
		return $form;
	}

?>
<script>
	jQuery(document).ready( function($){
		
		if( $( 'form[id^="gform_"] input[aria-describedby^="gfield_consent_description_"]' ).length ){
			$( 'input[aria-describedby^="gfield_consent_description_"]' ).each( function(){
				var list_field = $(this).closest('li.gfield');
				var list_id = $(list_field).attr( 'id' );
				var list_description = list_field.find( '.gfield_description.gfield_consent_description' );
				var list_label = list_field.find( 'label.gfield_label' );
				list_description.hide();
				list_label.wrapInner( '<a href="#" linkfor="list_id"  class="consent-accordian"></a>' );
				list_label.find('.consent-accordian').prepend('<div class="gawsClickLabel">CLICK TO OPEN</div>')
				list_description.wrapInner( '<div class="inner_consent_description"></div>' );
			});
			
			$('a.consent-accordian').click( function(e) {
				e.preventDefault(); 
				
				var list_field = $(this).closest('li.gfield');
				list_field.find( '.gfield_description.gfield_consent_description' ).toggle( 350 );

				
				var observer;
				document.body.scrollTop = -1000; // For Safari
				document.documentElement.scrollTop = -1000; // For Chrome, Firefox, IE and Opera
				if( !observer ){
					observer = new MutationObserver( function( mutations ) {
						var itemsProcessed = 0;
						mutations.forEach( function( mutationRecord, index, array ) {
							setTimeout(() => { 
								itemsProcessed++;
								if(itemsProcessed === array.length) {
									$( '.gfield_consent_description' ).each( function(){
										var end_style = $( this ).attr('style');
										var this_link = $( this ).parent('li.gfield').find('label.gfield_label div.gawsClickLabel');
										if( end_style.search("display: none;") ){
											if( this_link[0].outerHTML.search("OPEN") ){
												this_link.text( "CLICK TO CLOSE" );
											}
										} else {
											this_link.text( "CLICK TO OPEN" );
										}
									});
									
								}
							}, 1500 );
							observer.disconnect();
						});

					});
				}
				var target = document.querySelector( '.gfield_consent_description' );
				observer.observe( target, { attributes : true, attributeFilter : ['style'] } );	
				
				
				return false; 
			});
		}
	});
</script>
<style>
.gawsClickLabel{
display: inline-block;
    background: #3e8000;
    padding: 5px 10px;
    margin: 5px;
    border-radius: 6px;
    color: white;
		border: 2px solid #0c1a00;
	transition: background 1s, border .5s;
}


.gawsClickLabel:hover{
	background: #56b300;
	border: 2px solid #254d00;
}
</style>
<?php
//			 }

return $form;
}
