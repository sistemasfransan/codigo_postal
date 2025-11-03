function EstadoConteo()
{
	try
	{	
		var btnEstado = document.getElementById( "btnEstado" );
		var menContador = document.getElementById( "menContador" );
		
			
		if( btnEstado.value == "Activar" )
		{
			Ajax( "ajax/estado.php", "estado=1", "menContador" );
			btnEstado.value = "Desactivar";
			btnEstado.innerHTML = "Desactivar";
		}
		else
		{
			Ajax( "ajax/estado.php", "estado=0", "menContador" );
			btnEstado.value = "Activar";
			btnEstado.innerHTML = "Activar";		
		}	
		
		
	}
	catch( e )
	{
		alert( "Error ValidarNegocio() " + e.message );
	}
}