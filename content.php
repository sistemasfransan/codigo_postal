<?
class Content
{
	var $conexion;
	
	function Content( $conexion )
	{
		$this -> conexion = $conexion;
		$this -> Menu();
	}
	
	function Menu()
	{
		if( $_SESSION[session_user] )
		{
			if( !$_GET[codOption] ) $_GET[codOption] = $_POST[codOption];
			
			if( $_GET[codOption] )
			{
				$option = "SELECT a.rutOption
						   FROM tab_options a,
								tab_permisos b
						   WHERE a.codOption = '$_GET[codOption]' AND
								 a.codOption = b.codOption AND
								 b.codPerfil = '".$_SESSION[session_user][codPerfil]."' ";

				$option = $this -> conexion -> Consultar( $option, "a" );
				
				if( $option[rutOption] )
					include( $option[rutOption] );
				else
					MessageAlert( "El Servicio No se Encuentra Disponible" );
			}
			else
				include( "mod/home.php" );
		}
		else
		{
			include( "login.php" );
		}
	}	
}

$content = new Content( $this -> conexion );
?>