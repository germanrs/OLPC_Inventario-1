( function( $ ) {
$( document ).ready(function() {
$('#cssmenu li.has-sub>a').on('click', function(){
		$(this).removeAttr('href');
		var element = $(this).parent('li');
		if (element.hasClass('open')) {
			element.removeClass('open');
			element.find('li').removeClass('open');
			element.find('ul').slideUp();
		}
		else {
			element.addClass('open');
			element.children('ul').slideDown();
			element.siblings('li').children('ul').slideUp();
			element.siblings('li').removeClass('open');
			element.siblings('li').find('li').removeClass('open');
			element.siblings('li').find('ul').slideUp();
		}
	});
});
} )( jQuery );

$(document).ready(function() {
	var url = window.location.href;
	var hiden = document.getElementById('hiden');
	var errors = document.getElementsByClassName('error');

	var controle = ""
	for(var i = 0; i < errors.length; i++)
	{
	   controle = (errors[i].innerHTML.indexOf('This value should not be blank.')!=-1) ? "error" : controle;
	}
	if((url.indexOf('edit')!=-1 || hiden.innerHTML.indexOf('/show')!=-1) || controle !=""){
		url = url + "#openModal";
		window.location.href = url;
	}
})
