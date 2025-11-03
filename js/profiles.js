// JavaScript Document

function ValidarPerfil( accion )
{
	try
	{
		var codPerfil = document.getElementById( "codPerfil" );
		var nomPerfil = document.getElementById( "nomPerfil" );
		var formulario = document.getElementById( "form" );
		var state = document.getElementById( "state" );
		
		if( !codPerfil.value )
		{
			alert( "El Codigo de Perfil es Requerido" );
			return codPerfil.focus();
		}
		
		if( !nomPerfil.value )
		{
			alert( "El Nombre del Perfil es Requerido" );
			return nomPerfil.focus();
		}
		
		if( confirm( "Esta Seguro de "+accion+" el Perfil" ) )
		{
			state.value = "regist";
			formulario.submit();
		}
		
	}
	catch( e )
	{
		alert( "Error " + e.message );
	}
}

function DesactivarPerfil()
{
	try
	{
		var formulario = document.getElementById( "form" );
		
		if( confirm( "Esta Seguro de Desactivar el Perfil" ) )
		{
			formulario.submit();
		}
	}
	catch( e )
	{
		alert( "Error " + e.message  );
	}
}

function ActivarPerfil()
{
	try
	{
		var formulario = document.getElementById( "form" );
		
		if( confirm( "Esta Seguro de Activar el Perfil" ) )
		{
			formulario.submit();
		}
	}
	catch( e )
	{
		alert( "Error " + e.message  );
	}
}