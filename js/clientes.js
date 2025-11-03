function ValidarCliente( Accion )
{
	try
	{	
		var codTipo = document.getElementById( "codTipo" );
		
		if( !codTipo.value )
		{
			alert( "El Tipo de Cliente es requerido" );
			return $('#codTipo_chzn .chzn-search input[type="text"]').focus();
		}
		
		var codUsuario = document.getElementById( "codUsuario" );
		
		if( !codUsuario.value )
		{
			alert( "El Vendedor es requerido" );
			return $('#codUsuario_chzn .chzn-search input[type="text"]').focus();
		}
		
		
		var nitCliente = document.getElementById( "nitCliente" );
		
		if( !nitCliente.value )
		{
			alert( "El Nit del Cliente es requerido" );
			return nitCliente.focus();
		}
		
		var nomCliente = document.getElementById( "nomCliente" );
		
		if( !nomCliente.value )
		{
			alert( "El Nombre del Cliente es requerido" );
			return nomCliente.focus();
		}
		
		
		var codCiudad = document.getElementById( "codCiudad" );
		
		if( !codCiudad.value )
		{
			alert( "La Ciudad es requerida" );
			return $('#codCiudad_chzn .chzn-search input[type="text"]').focus();
		}
		
		var dirCliente = document.getElementById( "dirCliente" );
		
		if( !dirCliente.value )
		{
			alert( "La Direccion del Cliente es requerida" );
			return dirCliente.focus();
		}
		
		
		var telCliente = document.getElementById( "telCliente" );
		
		if( !telCliente.value )
		{
			alert( "El Telefono del Cliente es requerido" );
			return telCliente.focus();
		}
		
		var celCliente = document.getElementById( "celCliente" );
		
		if( !celCliente.value )
		{
			alert( "El Celular del Cliente es requerido" );
			return celCliente.focus();
		}
		
		
		var webCliente = document.getElementById( "webCliente" );
		
		if( !webCliente.value )
		{
			alert( "El Nit del Cliente es requerido" );
			return webCliente.focus();
		}
		
		var corCliente = document.getElementById( "corCliente" );
		
		if( !corCliente.value )
		{
			alert( "El Correo del Cliente es requerido" );
			return corCliente.focus();
		}	
		
		
		
		if( confirm( "Esta seguro de " + Accion + " el Cliente?" ) )
		{		
			var formulario = document.getElementById( "form" );
			var state = document.getElementById( "state" );
			
			state.value = "insert";
			
			formulario.submit();
		}		
	}
	catch( e )
	{
		alert( "Error ValidarCliente() " + e.message );
	}
}