// JavaScript Document
function onEnterSubmit( myfield ,e )
{
	var keycode;
	if (window.event) keycode = window.event.keyCode;
	else if (e) keycode = e.which;
	else return true;
	
	if ( keycode == 13 )
	{
	   ValidateLogin();
	   return false;
	}
	else
	   return true;
}

function ValidateLogin()
{
	try
	{
		var user = document.getElementById("user");
		var pass = document.getElementById("pass");
		var formulario = document.getElementById("formulario");
		
		if( !user.value )
		{
			return user.focus();
		}
		
		if( !pass.value )
		{
			return pass.focus();
		}
		
		formulario.submit();
	}
	catch( e )
	{
		alert( " Error " + e.message );
	}
}