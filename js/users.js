

function ValidarUsuario( accion )
{
	try
	{
		var nomUsuario = document.getElementById( "nomUsuario" );
		var codUsuario = document.getElementById( "codUsuario" );
		var emaUsuario = document.getElementById( "emaUsuario" );
		var clvUsuario = document.getElementById( "clvUsuario" );
		var clvUsuario2 = document.getElementById( "clvUsuario2" );
		var codPerfil = document.getElementById( "codPerfil" );
		var codAlmacen = document.getElementById( "codAlmacen" );
		
		var formulario = document.getElementById( "form" );
		var state = document.getElementById( "state" );
		
		if( !nomUsuario.value )
		{
			alert( "EL Nombre del Usuario es Requerido" );
			return nomUsuario.focus();
		}
		
		if( !codUsuario.value )
		{
			alert( "El Login es Requerido" );
			return codUsuario.focus();
		}
		
		if( clvUsuario && !clvUsuario.value )
		{
			alert( "La Clave es Requerida" );
			return clvUsuario.focus();
		}
		
		if( clvUsuario2 && !clvUsuario2.value )
		{
			alert( "La Confirmacion es Requerida" );
			return clvUsuario2.focus();
		}
		
		if( clvUsuario && clvUsuario2 && clvUsuario.value != clvUsuario2.value )
		{
			alert( "La Confirmacion no Coincide con la Clave." );
			return clvUsuario2.focus();
		}	
		
		if( !codPerfil.value )
		{
			alert( "El Perfil es Requerido" );
			return codPerfil.focus();
		}
		
		if( !emaUsuario.value )
		{
			alert( "El Email es Requerido" );
			return emaUsuario.focus();
		}
		
		if( confirm( "Esta Seguro de "+accion+" el Usuario." ) )
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

function DesactivarUsuario()
{
	try
	{
		var formulario = document.getElementById( "form" );
		
		if( confirm( "Esta Seguro de Desactivar el Usuario" ) )
		{
			formulario.submit();
		}
	}
	catch( e )
	{
		alert( "Error " + e.message  );
	}
}

function ActivarUsuario()
{
	try
	{
		var formulario = document.getElementById( "form" );
		
		if( confirm( "Esta Seguro de Activar el Usuario" ) )
		{
			formulario.submit();
		}
	}
	catch( e )
	{
		alert( "Error " + e.message  );
	}
}

function ValidarClave()
{
	try
	{
		var clvUsuario = document.getElementById( "clvUsuario" );
		var clvUsuario2 = document.getElementById( "clvUsuario2" );
		
		var formulario = document.getElementById( "form" );
		var state = document.getElementById( "state" );
		
		if( clvUsuario && !clvUsuario.value )
		{
			alert( "La Clave es Requerida" );
			return clvUsuario.focus();
		}
		
		if( clvUsuario2 && !clvUsuario2.value )
		{
			alert( "La Confirmacion es Requerida" );
			return clvUsuario2.focus();
		}
		
		if( clvUsuario && clvUsuario2 && clvUsuario.value != clvUsuario2.value )
		{
			alert( "La Confirmacion no Coincide con la Clave." );
			return clvUsuario2.focus();
		}	
		
		
		if( confirm( "Esta Seguro de Cambiar la Clave." ) )
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