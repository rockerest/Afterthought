$( '#title' ).click(function(){
	go_here('index.php');
});

$( '#head_logout' ).hover(
	function(){
		$(this).children('img').attr('src', 'images/icons/door-open-out.png');
	},
	function(){
		$(this).children('img').attr('src', 'images/icons/door--arrow.png');
	}
);

//element default left
Tipped.create('.tipped-left', {
	hideOn: [
				{ element: 'self', event: 'mouseleave' },
				{ element: 'tooltip', event: 'mouseenter' }
			],
	hook: 'leftmiddle'
});

//element fixed below
$('.fixedElementTipped-below').each(
	function()
	{
		var element = $(this).attr('data-tippedID');
		Tipped.create(
			this,
			$('#' + element).clone(true, true).removeAttr('id')[0],
			{
				hook: { target:  'bottomleft', tooltip: 'topleft' },
				skin: 'light'
			}
		);
	}
);