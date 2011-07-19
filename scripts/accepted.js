var msgboxes = $('div.gutter');

msgboxes.each(function( index ){
	var textNode = $( this ).contents();
	var text = $.trim(textNode[0].data);
	if( text.substr(0, 33) == "You have logged in successfully, " )
	{
		$( this ).fadeOut(3500);
	}
});

log.binfo( 'Your user is registered as "John Doe" which is why you\'re greeted as "John" :D' );