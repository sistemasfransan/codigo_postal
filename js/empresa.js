
function ValidarDatos()
{
	try
	{
		var nitEmpresa = document.getElementById( "nitEmpresa" );
		var nomEmpresa = document.getElementById( "nomEmpresa" );
		var gerEmpresa = document.getElementById( "gerEmpresa" );
		var dirEmpresa = document.getElementById( "dirEmpresa" );
		var telEmpresa = document.getElementById( "telEmpresa" );
		var celEmpresa = document.getElementById( "celEmpresa" );
		var corEmpresa = document.getElementById( "corEmpresa" );
		var ciuEmpresa = document.getElementById( "ciuEmpresa" );
		var codRegimen = document.getElementById( "codRegimen" );
		
		var codConcepto = document.getElementById( "codConcepto" );
		
		var formulario = document.getElementById( "form" );
		var state = document.getElementById( "state" );
		
		if( !nitEmpresa.value )
		{
			alert( "El NIT es Requerido" );
			return nitEmpresa.focus();
		}
		
		if( !nomEmpresa.value )
		{
			alert( "El Nombre es Requerido" );
			return nomEmpresa.focus();
		}
		
		if( !gerEmpresa.value )
		{
			alert( "El Encargado es Requerido" );
			return gerEmpresa.focus();
		}
		
		if( !dirEmpresa.value )
		{
			alert( "La Direccion es requerida" );
			return dirEmpresa.focus();
		}
		
		if( !corEmpresa.value )
		{
			alert( "El Correo es requerido" );
			return corEmpresa.focus();
		}
		
		if( !ciuEmpresa.value )
		{
			alert( "La Ciudad es requerida" );
			return ciuEmpresa.focus();
		}
		
		if( !codRegimen.value )
		{
			alert( "El Regimen es requerido" );
			return codRegimen.focus();
		}
		
		if( confirm( "¿ Esta Seguro de guardar la informacion ? " ) )
		{
			state.value = "save";
			formulario.submit();
		}		
	}
	catch( e )
	{
		alert( "Error " + e.message );
	}
}