function ValidarNegocio( Accion )
{
	try
	{	
		var codSucursal = document.getElementById( "codSucursal" );
		
		if( codSucursal && !codSucursal.value )
		{
			alert( "La sucursal es requerida" );
			return $('#codSucursal_chzn .chzn-search input[type="text"]').focus();
		}
		
		var codUsuario = document.getElementById( "codUsuario" );
		
		if( !codUsuario.value )
		{
			alert( "El usuario es requerido" );
			return $('#codUsuario_chzn .chzn-search input[type="text"]').focus();
		}
		
		/*var fecNegocio = document.getElementById( "fecNegocio" );
		
		if( !fecNegocio.value )
		{
			alert( "La fecha es requerida" );
			return fecNegocio.focus();
		}*/
		
		if( confirm( "Esta seguro de " + Accion + " el negocio?" ) )
		{		
			var formulario = document.getElementById( "form" );
			var state = document.getElementById( "state" );
			
			state.value = Accion;
			
			formulario.submit();
		}		
	}
	catch( e )
	{
		alert( "Error ValidarNegocio() " + e.message );
	}
}